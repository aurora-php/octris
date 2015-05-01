<?php

/*
 * This file is part of the octris/octris.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * Router script for httpd command.
 */

ob_end_clean();

if (file_exists($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])) {
    // serve static files
    return false;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/index.php');
