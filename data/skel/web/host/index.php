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
$registry = \Octris\Core\Registry::getInstance();
$registry->set('OCTRIS_APP', '{{$vendor}}-{{$module}}', \Octris\Core\Registry::T_READONLY);
$registry->set('OCTRIS_BASE', realpath(__DIR__ . '/../'), \Octris\Core\Registry::T_READONLY);
$registry->set('config', function () {
    return new \Octris\Core\Config('{{$vendor}}-{{$module}}', 'config');
}, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY);

// run application
$app = new App\Main();
$app->process();
