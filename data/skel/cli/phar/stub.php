#!/usr/bin/env php
<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
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
 * PHAR stub for {{$vendor}}-{{$module}}.
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

require_once('phar://{{$module}}.phar/vendor/autoload.php');
require_once('phar://{{$module}}.phar/libs/autoloader.php');

// import environment
\Octris\Core\Provider::set('env', $_ENV);

// load application configuration
$registry = \Octris\Core\Registry::getInstance();
$registry->set('OCTRIS_APP', '{{$vendor}}-{{$module}}', \Octris\Core\Registry::T_READONLY);
$registry->set('OCTRIS_BASE', __DIR__, \Octris\Core\Registry::T_READONLY);
$registry->set('config', function () {
    return new \Octris\Core\Config('{{$vendor}}-{{$module}}', 'config');
}, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY);

// run application
$app = new {{$namespace}}\app();
$app->run();

__HALT_COMPILER();
