<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   Isotope Simple ERP
 * @author    Benny Born <benny.born@numero2.de>
 * @license   LGPL
 * @copyright 2016 numero2 - Agentur für Internetdienstleistungen
 */


namespace numero2\IsotopeSimpleERP;

use Isotope\Model\Config;
use Isotope\Model\ProductCollection\Order;


class SimpleERP extends \System
{

	/**
	 * Updates the counts on all bought products
	 * @param Isotope\Model\ProductCollection
	 * @param \stdClass
	 * @param string
	 * @param integer
	 * @param mixed
	 */
	public function updateProductCount( \Isotope\Model\ProductCollection\Order $objOrder )
	{

		if( empty($objOrder->id) )
			return false;

        foreach( $objOrder->getItems() as $objItem )
        {

			$objProduct = NULL;
			$objProduct = $objItem->getProduct(true);

        	if( !empty($objProduct->simple_erp_count) && $objProduct->simple_erp_count > 0 )
        	{
    			// decrease available quantity
				$objProduct->simple_erp_count = $objProduct->simple_erp_count - $objItem->quantity;

				// set product suppressed if there is no quantity left and the option is checked
				if( $objProduct->simple_erp_disable_on_zero && $objProduct->simple_erp_count <= 0 ) {
					$objProduct->simple_erp_count = 0;
					$objProduct->published = 0;
				}

				// update product
				$objProduct->save();
        	}
        }
	}


    /**
     * Show messages for new order status
     *
     * @return string
     */
	public function getSystemMessages()
	{

		$this->import('Database');

		$objUnavailProducts = NULL;
		$objUnavailProducts = $this->Database->prepare("SELECT COUNT(id) as num_unavail FROM tl_iso_product WHERE simple_erp_disable_on_zero = '1' AND simple_erp_count = '0'")->execute();

		if( $objUnavailProducts->num_unavail )
        {
			return '<p class="tl_error">' . sprintf($GLOBALS['TL_LANG']['MSC']['simple_erp_non_avail'], $objUnavailProducts->num_unavail) . '</p>';
        }
	}
}