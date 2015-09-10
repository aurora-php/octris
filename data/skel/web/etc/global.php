<?php

/*
 * This file is part of the '{{$vendor}}/{{$package}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Global application configuration.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */

namespace Octris\Rtest;

define('OCTRIS_APP_VENDOR', '{{$vendor}}');
define('OCTRIS_APP_NAME', '{{$package}}');
define('OCTRIS_APP_BASE', realpath(__DIR__ . '/../'));

\Octris\Core\Registry::getInstance()
    ->set('config', function () {
            return new \Octris\Core\Config('config');
        }, \Octris\Core\Registry::T_SHARED | \Octris\Core\Registry::T_READONLY)
    ->set('createTemplate', function($registry) {
            $tpl = new \Octris\Core\Tpl();

            $tpl->setL10n(\Octris\Core\L10n::getInstance());
            $tpl->setOutputPath('tpl', OCTRIS_APP_BASE . '/cache/templates_c/');
            $tpl->setOutputPath('css', OCTRIS_APP_BASE . '/host/styles/');
            $tpl->setOutputPath('js', OCTRIS_APP_BASE . '/host/libsjs/');
            $tpl->setResourcePath('css', OCTRIS_APP_BASE . '/styles/');
            $tpl->setResourcePath('js', OCTRIS_APP_BASE . '/libsjs/');
            $tpl->addSearchPath(OCTRIS_APP_BASE . '/templates/');

            return $tpl;
        }, \Octris\Core\Registry::T_READONLY);
