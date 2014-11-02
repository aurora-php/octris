<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}};

/**
 * Application loader.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
/**/

// include core web application library
@include_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../libs/autoloader.php');

// load application configuration
$registry = \octris\core\registry::getInstance();
$registry->set('OCTRIS_APP', '{{$vendor}}-{{$module}}');
$registry->set('OCTRIS_BASE', realpath(__DIR__ . '/../'));
$registry->set('config', function () {
    return new \octris\core\config('{{$vendor}}-{{$module}}', 'config');
}, \octris\core\registry::T_SHARED | \octris\core\registry::T_READONLY);

// run application
app\main::getInstance()->process();
