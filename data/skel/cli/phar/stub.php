#!/usr/bin/env php
<?php

/*
 * This file is part of the '{{$vendor}}/{{$package}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * WARNING!!!
 *
 * MODIFICATION OF THIS FILE MAY LEAD TO UNEXPECTED BEHAVIOUR!
 */

/**
 * PHAR stub for {{$vendor}}-{{$package}}.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
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

require_once('phar://{{$package}}.phar/vendor/autoload.php');
require_once('phar://{{$package}}.phar/libs/Autoloader.php');

// import environment
\Octris\Core\Provider::set('env', $_ENV);

// load application configuration
define('OCTRIS_APP_VENDOR', '{{$vendor}}');
define('OCTRIS_APP_NAME', '{{$package}}');
define('OCTRIS_APP_BASE', realpath(__DIR__));

$registry = \Octris\Core\Registry::getInstance();
$registry->set('config', function () {
    return new \Octris\Core\Config('config');
}, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY);

// run application
$app = new {{$namespace}}\App();
$app->run();

__HALT_COMPILER();
