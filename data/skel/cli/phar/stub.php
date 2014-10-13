#!/usr/bin/env php
<?php

/*
 * This file is part of the '{{$directory}}' package.
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
 * PHAR stub for {{$directory}}.
 *
 * @octdoc      h:phar/stub
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
/**/

if (!class_exists('PHAR')) {
    print 'unable to execute -- wrong PHP version\n';
    exit(1);
}

Phar::mapPhar();

require_once('phar://{{$module}}.phar/libs/autoloader.class.php');

// load application configuration
$registry = \org\octris\core\registry::getInstance();
$registry->set('OCTRIS_APP', '{{$directory}}', \org\octris\core\registry::T_READONLY);
$registry->set('OCTRIS_BASE', __DIR__, \org\octris\core\registry::T_READONLY);
$registry->set('config', function() {
    return new \org\octris\core\config('{{$directory}}', 'config');
}, \org\octris\core\registry::T_SHARED | \org\octris\core\registry::T_READONLY);

// run application
$main = new {{$namespace}}\main();
$main->run();

__HALT_COMPILER();