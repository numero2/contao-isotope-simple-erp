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
 * Table tl_iso_product
 */
$GLOBALS['TL_DCA']['tl_iso_product']['fields']['simple_erp_count'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_product']['simple_erp_count'],
    'exclude'       => true,
    'search'        => true,
    'sorting'       => true,
    'inputType'     => 'text',
    'eval'          => array( 'mandatory'=>false, 'tl_class'=>'w50' ),
    'attributes'    => array( 'legend'=>'general_legend', 'multilingual'=>false, 'fixed'=>true, 'variant_fixed'=>true, 'fe_sorting'=>true ),
    'sql'           => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_iso_product']['fields']['simple_erp_disable_on_zero'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_product']['simple_erp_disable_on_zero'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => array( 'tl_class'=>'w50' ),
    'attributes'    => array( 'legend'=>'general_legend', 'fixed'=>true, 'variant_fixed'=>true ),
    'sql'           => "char(1) NOT NULL default ''",
);