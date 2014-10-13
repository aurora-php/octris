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
 * Application launcher.
 *
 * @octdoc      h:{{$module}}/{{$module}}.php
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
/**/

require_once(__DIR__ . '/libs/autoloader.class.php');

// load application configuration
$registry = \org\octris\core\registry::getInstance();
$registry->set('OCTRIS_APP', '{{$directory}}', \org\octris\core\registry::T_READONLY);
$registry->set('OCTRIS_BASE', __DIR__, \org\octris\core\registry::T_READONLY);
$registry->set('config', function() {
    return new \org\octris\core\config('{{$directory}}', 'config');
}, \org\octris\core\registry::T_SHARED | \org\octris\core\registry::T_READONLY);

// run application
$main = new {{$module}}\main();
$main->run();
