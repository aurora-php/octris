#!/usr/bin/env php
<?php

/**
 * Helper tool for compiling templates.
 *
 * @copyright   copyright (c) 2015 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */

if ($argc !== 2) {
    printf("usage: %s <project-path>\n", $argv[0]);
    die(255);
}

$base = rtrim($argv[1], '/');

if (!is_file($base . '/etc/global.php')) {
    printf("global app configuration not found \"%s\"!\n", $base . '/etc/global.php');
    die(255);
}

@include_once(__DIR__ . '/../vendor/autoload.php');

require_once($base . '/etc/global.php');

@include_once($base . '/vendor/autoload.php');
@include_once($base . '/libs/Autoloader.php');

// compile templates
$registry = \Octris\Core\Registry::getInstance();
$tpl = $registry->createTemplate;

$next_path = '';
$total_time = 0;

foreach ($tpl->getTemplatesIterator() as $file => $path) {
    if ($next_path != $path) {
        $next_path = $path;

        printf("\n%s:\n", dirname($path));
    }

    printf("%s ... ", $file);

    $start = microtime(true);

    $tpl->compile($file, \Octris\Core\Tpl::ESC_HTML);

    $end = microtime(true);

    printf("%1.4fs\n", $end - $start);

    $total_time += ($end - $start);
}

printf("\nDone, total time: %1.4fs.\n\n", $total_time);
