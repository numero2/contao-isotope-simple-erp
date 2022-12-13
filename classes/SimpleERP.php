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


class SimpleERP extends System
{

    /** 
     * Concatenates a class $sCssClass to previously existing classes
     * 
     * css classes are kept in the second entry of a serialized field
     *
     * @return none
     */

    private function setCssID(string &$serCssID, string $sCssClass)
    {
        $aCssID = unserialize($serCssID);
        if (!$aCssID or !in_array($sCssClass, $aCssID)) {
            $aCssID[1] .= ' ' . $sCssClass;
        }
        $serCssID = serialize($aCssID);
    }


    /**
     * Updates the counts on all bought products
     *
     * @param Isotope\Model\ProductCollection $objOrder
     *
     * @return none
     */
    public function updateProductCount(IsotopeProductCollection $objOrder)
    {
        if (empty($objOrder->id))
            return false;

        foreach ($objOrder->getItems() as $objItem) {

            $objProduct = NULL;
            $objProduct = $objItem->getProduct(true);

            if (!empty($objProduct->simple_erp_count) && $objProduct->simple_erp_count > 0) {

                // decrease available quantity
                $objProduct->simple_erp_count = $objProduct->simple_erp_count - $objItem->quantity;

                // set product suppressed and css class and quantity zero if there is no quantity left and the option is checked
                if ($objProduct->simple_erp_disable_on_zero && $objProduct->simple_erp_count <= 0) {

                    $objProduct->simple_erp_count = 0;
                    $objProduct->published = '';

                    //concatenate class "outOfStock" to previously existing css-classes
                    $serCssID = $objProduct->cssID;
                    $this->setCssID($serCssID, 'outOfStock');

                    \Database::getInstance()->prepare("UPDATE " . Product::getTable() . " SET simple_erp_count = ?, published = ?,  cssID = ?  WHERE id = ?")->execute($objProduct->simple_erp_count, $objProduct->published, $serCssID, $objProduct->id);
                }

                // set css class and quantity zero if there is no quantity left
                elseif ($objProduct->simple_erp_count <= 0) {

                    $objProduct->simple_erp_count = 0;

                    //concatenate class "outOfStock" to previously existing css-classes
                    $serCssID = $objProduct->cssID;
                    $this->setCssID($serCssID, 'outOfStock');

                    \Database::getInstance()->prepare("UPDATE " . Product::getTable() . " SET simple_erp_count = ?,   cssID = ?  WHERE id = ?")->execute($objProduct->simple_erp_count, $serCssID, $objProduct->id);
                }

                // just set new quantity in any other case
                else {

                    \Database::getInstance()->prepare("UPDATE " . Product::getTable() . " SET simple_erp_count = ? WHERE id = ?")->execute($objProduct->simple_erp_count, $objProduct->id);
                }
            }
        }
    }


    /**
     * Checks if the requested quantity exceeds our stock when adding product to cart.
     * Reduce requested quantity if neccessary; set css class "reserved" if applicable
     *
     * @param Isotope\Model\Product $objProduct
     * @param Isotope\Model\ProductCollection $objCollection
     *
     * @return boolean
     */
    public function checkQtyForCollection(Product $objProduct, $intQuantity, IsotopeProductCollection $objCollection)
    {

        if ($objProduct->simple_erp_count === '' || $objProduct->simple_erp_count === null) {
            return $intQuantity;
        }

        if ($objProduct->simple_erp_count > 0) {

            // find product in cart 
            $oInCart = null;
            $oInCart = $objCollection->getItemForProduct($objProduct);
            $qtyInCart = $oInCart->quantity ?? 0;   //quantity already in cart

            // remaining quantity to be requested
            $qtyAddToCart = $objProduct->simple_erp_count - $qtyInCart;
            $qtyAddToCart = $qtyAddToCart < 0 ? 0 : $qtyAddToCart;  // min. zero

            // remaining quantity <= newly requested quantity: set css-class "reserved"
            if ($qtyAddToCart <= $intQuantity) {

                // concatenate new class "reserved" to previously existing classes
                $serCssID = $objProduct->cssID;
                $this->setCssID($serCssID, 'reserved');
                \Database::getInstance()->prepare("UPDATE " . Product::getTable() . " SET  cssID = ?  WHERE id = ?")->execute($serCssID, $objProduct->id);

                // remaining quantity < demanded quantity: message warns about the decrease in order
                if ($qtyAddToCart < $intQuantity) {

                    Message::addError(sprintf(
                        $GLOBALS['TL_LANG']['ERR']['simpleErpQuantityNotAvailable'],
                        $objProduct->getName(),
                        $objProduct->simple_erp_count
                    ));
                }

                return $qtyAddToCart; // full or reduced request into cart
            }

            // more quantity remaining than requested
            else {
                return $intQuantity; // full request into cart
            }
        }

        // Product out of stock

        // concatenate new class "outOfStock" to previously existing classes
        $serCssID = $objProduct->cssID;
        $this->setCssID($serCssID, 'outOfStock');
        \Database::getInstance()->prepare("UPDATE " . Product::getTable() . " SET  cssID = ?  WHERE id = ?")->execute($serCssID, $objProduct->id);

        Message::addError(sprintf(
            $GLOBALS['TL_LANG']['MSC']['simpleErpProductOutOfStock'],
            $objProduct->getName()
        ));

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
    public function updateQtyInCollection($objItem, $arrSet, $objCart)
    {

        $objProduct = null;
        $objProduct = $objItem->getProduct();

        if ($objProduct->simple_erp_count > 0) {

            if (array_key_exists('quantity', $arrSet) && $arrSet['quantity'] && $arrSet['quantity'] > $objProduct->simple_erp_count) {

                $arrSet['quantity'] = $objProduct->simple_erp_count;

                Message::addError(sprintf(
                    $GLOBALS['TL_LANG']['ERR']['simpleErpQuantityNotAvailable'],
                    $objProduct->getName(),
                    $objProduct->simple_erp_count
                ));
            }
        }

        return $arrSet;
    }


    /**
     * Show messages for products with 'no quantity available' if 'suppress on zero' and yet published 
     *
     * @return string
     */
    public function getSystemMessages()
    {

        $this->import('Database');

        $aMessages = [];

        $oProducts = null;
        $oProducts = Product::findBy(['simple_erp_disable_on_zero=?', 'tl_iso_product.published=?', 'tl_iso_product.simple_erp_count=?'], [1, 1, '0']);

        if ($oProducts) {

            while ($oProducts->next()) {

                $aMessages[] = '<p class="tl_error">' . sprintf(
                    $GLOBALS['TL_LANG']['MSC']['simpleErpProductOutOfStock'],
                    $oProducts->name
                ) . '</p>';
            }
        }

        return implode('', $aMessages);
    }
}
