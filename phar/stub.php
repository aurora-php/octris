#!/usr/bin/env php
<?php

/*
 * This file is part of the 'octris/octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Octris PHAR stub.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
/**/

if (version_compare(PHP_VERSION, '5.6.0') < 0) {
    printf(
        "%s: PHP-5.6.0 or newer is required, your version is '%s'!\n",
        basename($argv[0]),
        PHP_VERSION
    );
    exit(1);
}

if (!class_exists('PHAR')) {
    printf(
        "%s: unable to execute, PHAR extension is not available\n",
        basename($argv[0])
    );
    exit(1);
}

Phar::mapPhar();

require_once('phar://octris.phar/vendor/autoload.php');
require_once('phar://octris.phar/libs/Autoloader.php');

// import environment
\Octris\Core\Provider::set('env', $_ENV);

// load application configuration
$registry = \Octris\Core\Registry::getInstance();
$registry->set('OCTRIS_APP_NAME', 'octris-octris', \Octris\Core\Registry::T_READONLY);
$registry->set('OCTRIS_APP_BASE', __DIR__, \Octris\Core\Registry::T_READONLY);
$registry->set('config', function () {
    return new \Octris\Core\Config('octris-octris', 'config');
}, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY);

// run application
$app = new \Octris\App();
$app->run();

__HALT_COMPILER();
