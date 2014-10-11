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
    use \org\octris\core\provider as provider;

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
         * Available commands.
         *
         * @octdoc  p:main/$commands
         * @type    array
         */
        protected $commands = array();
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
         * Determine available "commands".
         *
         * @octdoc  m:main/getCommands
         */
        protected function getCommands()
        /**/
        {
            $this->commands = array();
            
            foreach (new \DirectoryIterator(__DIR__ . '/command/') as $file) {
                if (!$file->isDot() && substr(($name = $file->getFilename()), -4) == '.php') {
                    $command = basename($name, '.class.php');
                    
                    $this->commands[$command] = '\\octris\\command\\' . $command;
                }
            }
            
            ksort($this->commands);
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
            
            $this->getCommands();
            
            array_shift($argv);
            
            if (!($arg = array_shift($argv))) {
                $this->showUsage();
                exit(1);
            }

            $help = false;

            switch ($arg) {
                case '--help':
                    $this->showUsage();
                    exit(1);
                case '--version':
                    printf("octris %s (%s)\n", self::T_VERSION, self::T_VERSION_DATE);
                    exit(1);
                case 'help':
                    $help = true;
                    $arg  = array_shift($argv);
                    /** FALL THRU **/
                default:
                    if (!(preg_match('/^[a-z]+$/', $arg))) {
                        $this->showUsage();
                        exit(1);
                    } elseif (!isset($this->commands[$arg])) {
                        printf("octris: '%s' is not a command\n", $arg);
                        exit(1);
                    } else {
                        $class = "\\octris\\command\\$arg";
                        
                        if ($help) {
                            printf("octris: manual for command '%s'\n\n", $arg);
                            print trim($class::getManual(), "\n") . "\n";
                            exit(1);
                        } else {
                            printf("octris: %s\n\n", $arg);
                            
                            provider::set('args', \org\octris\core\app\cli::getOptions($argv));
                            
                            $instance = new $class();
                        
                            exit($instance->run());
                        }
                    }
            }

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
", 'v' . self::T_VERSION);

            $size = array_reduce(array_keys($this->commands), function($size, $item) {
                return max($size, strlen($item));
            }, 0);

            foreach ($this->commands as $command => $class) {
                printf("    %-" . $size . "s    %s\n", $command, $class::getDescription());
            }
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

    spl_autoload_register(array('\octris\main', 'autoload'));
}
