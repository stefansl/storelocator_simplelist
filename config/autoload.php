<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Storelocator_sgv_simplelist
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'ModuleStoreLocatorSimpleList' => 'system/modules/storelocator_simplelist/ModuleStoreLocatorSimpleList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_storelocator_simplelist' => 'system/modules/storelocator_simplelist/templates',
));
