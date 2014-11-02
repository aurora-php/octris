<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris;

/**
 * Autoloader.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Autoloader
{
    /**
     * Class Autoloader.
     *
     * @param   string          $class              Class extends  to load.
     */
    public static function autoload($class)
    {
        if (strpos($class, 'Octris\\') === 0) {
            $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 7)) . '.php';

            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
}

spl_autoload_register(array('\Octris\Autoloader', 'autoload'), true, true);
