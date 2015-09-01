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

// load application configuration
$registry = \Octris\Core\Registry::getInstance();
$registry->set('OCTRIS_APP_VENDOR', '{{$vendor}}', \Octris\Core\Registry::T_READONLY);
$registry->set('OCTRIS_APP_NAME', '{{$package}}', \Octris\Core\Registry::T_READONLY);
$registry->set('OCTRIS_APP_BASE', realpath(__DIR__ . '/../'), \Octris\Core\Registry::T_READONLY);
$registry->set('config', function () {
    return new \Octris\Core\Config('config');
}, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY);
$registry->set('createTemplate', function($registry) {
    $tpl = new \Octris\Core\Tpl();

    $tpl->setL10n(\Octris\Core\L10n::getInstance());
    $tpl->setOutputPath('tpl', $registry->OCTRIS_APP_BASE . '/cache/templates_c/');
    $tpl->setOutputPath('css', $registry->OCTRIS_APP_BASE . '/host/styles/');
    $tpl->setOutputPath('js', $registry->OCTRIS_APP_BASE . '/host/libsjs/');
    $tpl->setResourcePath('css', $registry->OCTRIS_APP_BASE . '/styles/');
    $tpl->setResourcePath('js', $registry->OCTRIS_APP_BASE . '/libsjs/');
    $tpl->addSearchPath($registry->OCTRIS_APP_BASE . '/templates/');

    return $tpl;
}, \Octris\Core\Registry::T_READONLY);

// run application
$app = new App\Main();
$app->setupRouter(function(\Octris\Router\RuleCollector $r) {
    $r->addRewrite(['GET', 'POST'], '/');     // default route map to pageRouter
}, $registry->OCTRIS_APP_BASE . '/cache/router.cache');
$app->process();
