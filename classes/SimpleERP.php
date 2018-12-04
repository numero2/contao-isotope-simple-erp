<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   Isotope Simple ERP
 * @author    Benny Born <benny.born@numero2.de>
 * @license   LGPL
 * @copyright 2017 numero2 - Agentur für Internetdienstleistungen
 */


namespace numero2\IsotopeSimpleERP;

use Isotope\Interfaces\IsotopeProductCollection;
use Isotope\Model\Config;
use Isotope\Model\ProductCollection;
use Isotope\Model\ProductCollection\Order;
use Isotope\Model\Product;


class SimpleERP extends \System {


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
     * Updates the counts on all bought products
     *
     * @param Isotope\Model\Product $objProduct
     * @param Isotope\Model\ProductCollection $objCollection
     *
     * @return boolean
     */
    public function checkQtyForCollection( Product $objProduct, $intQuantity, IsotopeProductCollection $objCollection ) {

        if( $objProduct->simple_erp_count > 0 ) {

            // check if we want to add more than we have in stock
            if( $intQuantity > $objProduct->simple_erp_count ) {
                return 0;
            }

            // find product in cart to check if the total quantity exceeds our stock
            $oInCart = NULL;
            $oInCart = $objCollection->getItemForProduct($objProduct);

            if( $oInCart && $oInCart->quantity >= $objProduct->simple_erp_count ) {
                return 0;
            }

        }

        return $intQuantity;
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

        if( $objItem->getProduct()->simple_erp_count > 0 ) {
            if( array_key_exists('quantity', $arrSet) && $arrSet['quantity'] && $arrSet['quantity'] > $objItem->getProduct()->simple_erp_count ) {
                $arrSet['quantity'] = $objItem->getProduct()->simple_erp_count;
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

        $objUnavailProducts = NULL;
        $objUnavailProducts = $this->Database->prepare("SELECT COUNT(id) as num_unavail FROM ".Product::getTable()." WHERE simple_erp_disable_on_zero = '1' AND simple_erp_count = '0'")->execute();

        if( $objUnavailProducts->num_unavail ) {
            return '<p class="tl_error">' . sprintf($GLOBALS['TL_LANG']['MSC']['simple_erp_non_avail'], $objUnavailProducts->num_unavail) . '</p>';
        }
    }
}
