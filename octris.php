#!/usr/bin/env php
<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Main application.
 *
 * @octdoc      h:octris/octris
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
/**/

if (version_compare(PHP_VERSION, '5.6.0') < 0) {
    printf("octris: PHP-5.6.0 or newer is required, your version is '%s'!\n", PHP_VERSION);
    exit(1);
}

require_once(__DIR__ . '/libs/autoloader.class.php');

// load application configuration
$registry = \org\octris\core\registry::getInstance();
$registry->set('OCTRIS_APP', 'octris', \org\octris\core\registry::T_READONLY);
$registry->set('OCTRIS_BASE', __DIR__, \org\octris\core\registry::T_READONLY);
$registry->set('config', function() {
    return new \org\octris\core\config('octris', 'config');
}, \org\octris\core\registry::T_SHARED | \org\octris\core\registry::T_READONLY);

// run application
$main = new \octris\main();
$main->run();
