#!/usr/bin/env php
<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Octris PHAR stub.
 *
 * @octdoc      h:phar/stub
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
/**/

if (!class_exists('PHAR')) {
    print 'unable to execute -- wrong PHP version\n';
    exit(1);
}

Phar::mapPhar();
include 'phar://octris.phar/main.class.php';

$main = new \octris\main();
$main->run();
__HALT_COMPILER();