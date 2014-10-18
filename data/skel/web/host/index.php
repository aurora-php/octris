<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}} {
    /**
     * Application loader.
     *
     * @octdoc      h:host/index
     * @copyright   copyright (c) {{$year}} by {{$company}}
     * @author      {{$author}} <{{$email}}>
     */
    /**/

    // include core web application library
    require_once(__DIR__ . '/../libs/app/autoloader.class.php');
    
    // load application configuration
    $registry = \octris\core\registry::getInstance();
    $registry->set('OCTRIS_APP', '{{$directory}}');
    $registry->set('OCTRIS_BASE', realpath(__DIR__ . '/../'));
    $registry->set('config', function() {
        return new \octris\core\config('{{$directory}}', 'config');
    }, \octris\core\registry::T_SHARED | \octris\core\registry::T_READONLY);

    // run application
    app\main::getInstance()->process();
}
