<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2021 Leo Feyer
 *
 * @package   Isotope Simple ERP
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   LGPL
 * @copyright 2021 numero2 - Agentur für digitales Marketing
 */


namespace numero2\IsotopeSimpleERP;

use Contao\System;
use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Message;
use Isotope\Model\Config;
use Isotope\Model\Product;
use Isotope\Model\ProductCollection;
use Isotope\Model\ProductCollection\Order;


class SimpleERP extends System {


    /**
     * Updates the counts on all bought products
     *
     * @param Isotope\Model\ProductCollection $objOrder
     *
     * @return none
     */
    public function updateProductCount( IsotopeProductCollection $objOrder ) {

        if( empty($objOrder->id) )
            return false;

        foreach( $objOrder->getItems() as $objItem ) {

            $objProduct = NULL;
            $objProduct = $objItem->getProduct(true);

            if( !empty($objProduct->simple_erp_count) && $objProduct->simple_erp_count > 0 ) {

                // decrease available quantity
                $objProduct->simple_erp_count = $objProduct->simple_erp_count - $objItem->quantity;

                // set product suppressed if there is no quantity left and the option is checked
                if( $objProduct->simple_erp_disable_on_zero && $objProduct->simple_erp_count <= 0 ) {
                    $objProduct->simple_erp_count = 0;
                    $objProduct->published = 0;
                }

                // update product
                \Database::getInstance()->prepare("UPDATE ".Product::getTable()." SET simple_erp_count = ?, published = ? WHERE id = ?")->execute( $objProduct->simple_erp_count, $objProduct->published, $objProduct->id );
            }
        }
    }


    /**
     * Checks if the given quantity exceeds our stock when adding product to cart
     *
     * @param Isotope\Model\Product $objProduct
     * @param Isotope\Model\ProductCollection $objCollection
     *
     * @return boolean
     */
    public function checkQtyForCollection( Product $objProduct, $intQuantity, IsotopeProductCollection $objCollection ) {

        if( $objProduct->simple_erp_count === '' ) {
            return $intQuantity;
        }

        if( $objProduct->simple_erp_count > 0 ) {

            // find product in cart to check if the total quantity exceeds our stock
            $oInCart = null;
            $oInCart = $objCollection->getItemForProduct($objProduct);

            if( $oInCart && ($oInCart->quantity+$intQuantity) >= $objProduct->simple_erp_count ) {

                $qtyAddToCart = $objProduct->simple_erp_count-$oInCart->quantity;
                $qtyAddToCart = $qtyAddToCart<0?0:$qtyAddToCart;

                if( !$qtyAddToCart ) {
                    Message::addError(sprintf(
                        $GLOBALS['TL_LANG']['ERR']['simpleErpQuantityNotAvailable']
                    ,   $objProduct->getName()
                    ,   $objProduct->simple_erp_count
                    ));
                }

                return $qtyAddToCart;

            } else {
                return $intQuantity;
            }
        }

        return false;
    }


    /**
     * Prevents setting the quantity in cart higher than given in simple_erp_count
     *
     * @param \Isotope\Model\ProductCollectionItem $objItem
     * @param array $arrSet
     * @param \Isotope\Model\ProductCollection\Cart $objCart
     *
     * @return array
     */
    public function updateQtyInCollection($objItem, $arrSet, $objCart) {

        $objProduct = null;
        $objProduct = $objItem->getProduct();

        if( $objProduct->simple_erp_count > 0 ) {

            if( array_key_exists('quantity', $arrSet) && $arrSet['quantity'] && $arrSet['quantity'] > $objProduct->simple_erp_count ) {

                $arrSet['quantity'] = $objProduct->simple_erp_count;

                Message::addError(sprintf(
                    $GLOBALS['TL_LANG']['ERR']['simpleErpQuantityNotAvailable']
                ,   $objProduct->getName()
                ,   $objProduct->simple_erp_count
                ));
            }
        }

        return $arrSet;
    }


    /**
     * Show messages for new order status
     *
     * @return string
     */
    public function getSystemMessages() {

        $this->import('Database');

        $aMessages = [];

        $oProducts = null;
        $oProducts = Product::findBy(['tl_iso_product.published=?','tl_iso_product.simple_erp_count=?'],[1,'0']);

        if( $oProducts ) {

            while( $oProducts->next() ) {

                $aMessages[] = '<p class="tl_error">' . sprintf(
                    $GLOBALS['TL_LANG']['MSC']['simpleErpProductOutOfStock']
                ,   $oProducts->name
                ) . '</p>';
            }
        }

        return implode('',$aMessages);
    }
}