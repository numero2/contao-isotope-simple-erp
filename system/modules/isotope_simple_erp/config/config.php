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


/**
 * Hooks
 */
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('numero2\IsotopeSimpleERP\SimpleERP', 'updateProductCount');
$GLOBALS['TL_HOOKS']['getSystemMessages'][] = array('numero2\IsotopeSimpleERP\SimpleERP', 'getSystemMessages');