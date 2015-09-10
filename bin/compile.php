#!/usr/bin/env php
<?php

/**
 * Helper tool for compiling templates.
 *
 * @copyright   copyright (c) 2015 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */

if ($argc !== 3) {
    printf("usage: %s <template-path> <cache-path>\n", $argv[0]);
    die(255);
}

@include_once(__DIR__ . '/../vendor/autoload.php');

$base = rtrim($argv[1], '/');

require_once($base . '/etc/global.php');

// compile templates
$registry = \Octris\Core\Registry::getInstance();
$tpl = $registry->createTemplate;

foreach ($tpl->getTemplatesIterator() as $file => $path) {
    print $path . "\n";

    $tpl->compile($file, \Octris\Core\Tpl::ESC_HTML);
}
