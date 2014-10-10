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
     * Main application class.
     *
     * @octdoc      c:libs/main
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class main 
    /**/
    {
        /**
         * Octris tool version and version-date.
         *
         * @octdoc  d:main/T_VERSION
         * @type    string
         */
        const T_VERSION = '0.0.1';
        const T_VERSION_DATE = '2014-00-00';
        /**/

        /**
         * Constructor.
         *
         * @octdoc  m:main/__construct
         * @param   
         */
        public function __construct()
        /**/
        {
        }

        /**
         * Run main application.
         *
         * @octdoc  m:main/run
         */
        public function run()
        /**/
        {
            global $argv;
            
            array_shift($argv);
            
            do {
                if (!($arg = array_shift($argv))) {
                    $this->showUsage();
                    exit(1);
                }
                
                switch ($arg) {
                    case '--help':
                        $this->showUsage();
                        exit(1);
                    case '--version':
                        printf("octris %s (%s)\n", self::T_VERSION, self::T_VERSION_DATE);
                        exit(1);
                    case 'help':
                        if (!(preg_match('/^[a-z]+/', array_shift($argv)))) {
                            $this->showUsage();
                        } else {
                            // TODO: show command help
                        }
                        exit(1);
                    default:
                        if (!preg_match('/^[a-z]+/', $arg)) {
                            $this->showUsage();
                            exit(1);
                        } else {
                            // TODO: process command
                        }
                        break(2);
                }
            } while(true);
            
            exit(0);
        }
    
        /**
         * Show usage information.
         *
         * @octdoc  m:main/showUsage
         */
        public function showUsage()
        /**/
        {
            printf("               __         .__        
  ____   _____/  |________|__| ______
 /  _ \_/ ___\   __\_  __ \  |/  ___/    OCTRiS framework tool
(  <_> )  \___|  |  |  | \/  |\___ \     copyright (c) 2014 by Harald Lapp
 \____/ \___  >__|  |__|  |__/____  >    http://github.com/octris/octris/
            \/%20s\/

usage: octris --help
usage: octris --version
usage: octris help <command>
usage: octris <command> [ARGUMENTS]

Commands:
    create      Create a new project.
", 'v' . self::T_VERSION);
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
            if (substr($classpath, 0, 6) == 'octris') {
                $file = __DIR__ . '/' . preg_replace('|\\\\|', '/', substr($classpath, 6)) . '.class.php';

                @include_once($file);
            }        
        }
    }

    spl_autoload_register(array('\octris\main', 'autoload'));
}
