<?php

/*
 * This file is part of the '{{$directory}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}}\libs {
    /**
     * Main application.
     *
     * @octdoc      c:libs/main
     * @copyright   copyright (c) {{$year}} by {{$company}}
     * @author      {{$author}} <{{$email}}>
     */
    class main
    /**/
    {
        /**
         * Constructor.
         *
         * @octdoc  m:main/__construct
         */
        public function __construct()
        /**/
        {
        }

        /**
         * Execute application.
         *
         * @octdoc  m:main/run
         */
        public function run()
        /**/
        {
        }
    
        /**
         * Class Autoloader.
         *
         * @octdoc  m:main/autoload
         * @param   string          $classpath              Path of class to load.
         */
        public static function autoload($classpath)
        /**/
        {
            if (substr($classpath, 0, 6) == '{{$module}}') {
                $file = __DIR__ . '/' . preg_replace('|\\\\|', '/', substr($classpath, 6)) . '.class.php';

                @include_once($file);
            } else {
                $classpath = preg_replace('|\\\\|', '.', ltrim($classpath, '\\\\'), 2);
                $classpath = preg_replace('|\\\\|', '/libs/', $classpath, 1);
                $classpath = preg_replace('|\\\\|', '/', $classpath);
                
                $file = __DIR__ . '/../vendor/' . $classpath . '.class.php';

                try {
                    include_once($file);
                } catch(\Exception $e) {
                }
            }
        }
    }

    spl_autoload_register(array('\{{$module}}\main', 'autoload'));
}

