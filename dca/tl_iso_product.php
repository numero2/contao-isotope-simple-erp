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
 * Table tl_iso_product
 */
$GLOBALS['TL_DCA']['tl_iso_product']['fields']['simple_erp_count'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_product']['simple_erp_count'],
    'exclude'       => true,
    'filter'        => true,
    'sorting'       => true,
    'inputType'     => 'text',
    'eval'          => [ 'mandatory'=>false, 'tl_class'=>'w50' ],
    'attributes'    => [ 'legend'=>'general_legend', 'multilingual'=>false, 'fe_sorting'=>true ],
    'sql'           => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_iso_product']['fields']['simple_erp_disable_on_zero'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_iso_product']['simple_erp_disable_on_zero'],
    'exclude'       => true,
    'filter'        => true,
    'inputType'     => 'checkbox',
    'eval'          => [ 'tl_class'=>'w50' ],
    'attributes'    => [ 'legend'=>'general_legend' ],
    'sql'           => "char(1) NOT NULL default ''",
);