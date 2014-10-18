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
 * Application launcher.
 *
 * @octdoc      h:{{$module}}/{{$module}}.php
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

require_once(__DIR__ . '/libs/autoloader.class.php');

// load application configuration
$registry = \octris\core\registry::getInstance();
$registry->set('OCTRIS_APP', '{{$vendor}}-{{$module}}', \octris\core\registry::T_READONLY);
$registry->set('OCTRIS_BASE', __DIR__, \octris\core\registry::T_READONLY);
$registry->set('config', function() {
    return new \octris\core\config('{{$vendor}}-{{$module}}', 'config');
}, \octris\core\registry::T_SHARED | \octris\core\registry::T_READONLY);

// run application
$app = new {{$module}}\app();
$app->run();
