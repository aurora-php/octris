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

function octris_router() {
    $known_exts = ['3gp', 'apk', 'avi', 'bmp', 'css', 'csv', 'doc', 'docx', 'flac', 'gif', 'gz', 'gzip', 'htm', 'html', 'ics', 'jpe', 'jpeg', 'jpg', 'js', 'kml', 'kmz', 'm4a', 'mov', 'mp3', 'mp4', 'mpeg', 'mpg', 'odp', 'ods', 'odt', 'oga', 'ogg', 'ogv', 'pdf', 'pdf', 'png', 'pps', 'pptx', 'qt', 'svg', 'swf', 'tar', 'text', 'tif', 'txt', 'wav', 'webm', 'wmv', 'xls', 'xlsx', 'xml', 'xsl', 'xsd', 'zip'];

    $path = $_SERVER["DOCUMENT_ROOT"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $name = basename($path);

    if (file_exists($path) && $name != 'index.php' && !is_file($path . '/index.php')) {
        if (is_dir($path)) {
            // forbidden to list directory index
            http_response_code(403);

            print '<h1>403</h1>';
        } else {
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if (in_array($ext, $known_exts)) {
                // built-in webserver can handle file extension
                return false;
            } else {
                // unhandled file extension
                $mime_type = mime_content_type($path);

                header('Content-type: ' . $mime_type);

                readfile($path);
            }
        }
    } else {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/index.php');
    }
}

ob_end_clean();

return octris_router();
