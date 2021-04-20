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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
    'numero2\IsotopeSimpleERP',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    // Classes
    'numero2\IsotopeSimpleERP\SimpleERP' => 'system/modules/isotope_simple_erp/classes/SimpleERP.php',
));