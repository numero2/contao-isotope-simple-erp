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


/**
 * HOOKS
 */
$GLOBALS['ISO_HOOKS']['postCheckout'][] = ['numero2\IsotopeSimpleERP\SimpleERP', 'updateProductCount'];
$GLOBALS['ISO_HOOKS']['addProductToCollection'][] = ['numero2\IsotopeSimpleERP\SimpleERP', 'checkQtyForCollection'];
$GLOBALS['ISO_HOOKS']['updateItemInCollection'][] = ['numero2\IsotopeSimpleERP\SimpleERP','updateQtyInCollection'];
$GLOBALS['TL_HOOKS']['getSystemMessages'][] = ['numero2\IsotopeSimpleERP\SimpleERP', 'getSystemMessages'];