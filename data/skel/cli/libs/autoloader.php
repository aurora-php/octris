<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}};

/**
 * Autoloader.
 *
 * @octdoc      c:libs/main
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
class Autoloader
{
    /**
     * Class Autoloader.
     *
     * @octdoc  m:autoloader/autoload
     * @param   string          $class              Class to load.
     */
    public static function autoload($class)
    {
        if (strpos($class, '{{$namespace}}\\') === 0) {
            $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, {{length(concat($namespace, '\\'))}})) . '.php';

            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
}

spl_autoload_register(array('\{{$module}}\Autoloader', 'autoload', true, true));
