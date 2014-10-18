<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$module}} {
    /**
     * Autoloader.
     *
     * @octdoc      c:libs/main
     * @copyright   copyright (c) {{$year}} by {{$company}}
     * @author      {{$author}} <{{$email}}>
     */
    class autoloader
    /**/
    {
        /**
         * Class Autoloader.
         *
         * @octdoc  m:autoloader/autoload
         * @param   string          $classpath              Path of class to load.
         */
        public static function autoload($classpath)
        /**/
        {
            if (strpos($classpath, '{{$module}}\\') === 0) {
                $file = __DIR__ . '/' . str_replace('\\', '/', substr($classpath, strlen('{{$module}}'))) . '.class.php';
            } else {
                $classpath = preg_replace('|\\\\|', '.', ltrim($classpath, '\\'), 2);
                $classpath = preg_replace('|\\\\|', '/libs/', $classpath, 1);
                $classpath = str_replace('\\', '/', $classpath);
                
                $file = __DIR__ . '/../vendor/' . $classpath . '.class.php';
            }
            
            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
    
    spl_autoload_register(array('\{{$module}}\autoloader', 'autoload'));
}
