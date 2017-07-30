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


/**
 * Hooks
 */
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('numero2\IsotopeSimpleERP\SimpleERP', 'updateProductCount');
$GLOBALS['ISO_HOOKS']['addProductToCollection'][] = array('numero2\IsotopeSimpleERP\SimpleERP', 'checkQtyForCollection');
$GLOBALS['TL_HOOKS']['getSystemMessages'][] = array('numero2\IsotopeSimpleERP\SimpleERP', 'getSystemMessages');

// TODO: prevent adding more to cart than in stock