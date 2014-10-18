<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace octris {
    /**
     * Autoloader.
     *
     * @octdoc      c:libs/autoloader
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
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
            if (substr($classpath, 0, 6) == 'octris') {
                $file = __DIR__ . '/' . str_replace('\\', '/', substr($classpath, 6)) . '.php';
            }
            
            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
    
    spl_autoload_register(array('\octris\autoloader', 'autoload'), true, true);
}
