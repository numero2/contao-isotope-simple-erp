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