<?php

/*
 * This file is part of the '{{$vendor}}/{{$package}}' package.
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
require_once(__DIR__ . '/../libs/Autoloader.php');

// load global application configuration
require_once(__DIR__ . '/../etc/global.php');

// run application
(function() {
    $router = new \Octris\Core\App\Web\Router\UrlBased(
        '{{$namespace}}\App\Entry',
        function(\Octris\Core\App\Web\Router\RuleCollector $r) {
            $r->addRewrite(['GET', 'POST'], '/');     // default route map to pageRouter
            $r->addRoute(['GET', 'POST'], '/service/{SERVICE}', new \{{$namespace}}\Services());
        },
        ($registry->config['mode'] == 'development' ? null : OCTRIS_APP_BASE . '/cache/router.cache')
    );
    $app = new App\Main($router);
    $app->run();
})(\Octris\Core\Registry::getInstance());
