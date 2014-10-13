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
    use \org\octris\cliff\options as options;

    /**
     * Main application class.
     *
     * @octdoc      c:libs/main
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class main extends \org\octris\cliff\app
    /**/
    {
        /**
         * Octris tool version and version-date.
         *
         * @octdoc  d:main/T_VERSION
         * @type    string
         */
        const T_VERSION = '0.0.3';
        const T_VERSION_DATE = '2014-10-13';
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
         * Parse command line options and return Array of them. The parameters are required to have
         * the following format:
         *
         * - short options: -l -a -b
         * - short options combined: -lab
         * - short options with value: -l val -a val -b "with whitespace"
         * - long options: --option1 --option2
         * - long options with value: --option=value --option value --option "with whitespace"
         *
         * @octdoc  m:main/getOptions
         * @param   array           $args               Allows to specify an array of arguments to use instead of global $argv.
         * @return  array                               Parsed command line parameters.
         */
        public function getOptions(array $args = null)
        /**/
        {
            if (is_null($args)) {
                $args = $GLOBALS['argv'];
                array_shift($args);
            }
 
            $opts = array();
            $key  = '';
            $idx  = 0;
 
            foreach ($args as $arg) {
                if (preg_match('/^-([a-zA-Z]+)$/', $arg, $match)) {
                    // short option, combined short options
                    $tmp  = str_split($match[1], 1);
                    $opts = array_merge(array_combine($tmp, array_fill(0, count($tmp), true)), $opts);
                    $key  = array_pop($tmp);
                    
                    continue;
                } elseif (preg_match('/^--([a-zA-Z][a-zA-Z0-9]+)(=.*|)$/', $arg, $match)) {
                    // long option
                    $key  = $match[1];
                    $opts = array_merge(array($key => true), $opts);
 
                    if (strlen($match[2]) == 0) {
                        continue;
                    }
 
                    $arg = substr($match[2], 1);
                } elseif (strlen($arg) > 1 && substr($arg, 0, 1) == '-') {
                    // invalid option format
                    throw new \Exception('invalid option format "' . $arg . '"');
                }
 
                if ($key == '') {
                    // no option name, add as numeric option
                    $opts[$idx++] = $arg;
                } else {
                    if (!is_bool($opts[$key])) {
                        // multiple values for this option
                        if (!is_array($opts[$key])) {
                            $opts[$key] = array($opts[$key]);
                        }
                        
                        $opts[$key][] = $arg;
                    } else {
                        $opts[$key] = $arg;
                    }
                    
                    $key = '';
                }
            }
 
            return $opts;
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
         * @octdoc  m:main/main
         */
        public function main()
        /**/
        {
            global $argv;
            
            $this->getCommands();
            
            // array_shift($argv);
            //
            // if (!($arg = array_shift($argv))) {
            //     $this->showUsage();
            //     exit(1);
            // }
            //
            $opts = new \org\octris\cliff\options();
            $opts->addOption(['h', 'help'])->setAction(function() {
                $this->showUsage();
                exit(1);
            });
            $opts->addOption(['version'])->setAction(function() {
                printf("octris %s (%s)\n", self::T_VERSION, self::T_VERSION_DATE);
                exit(1);
            });
            
            // help command
            $opts->addCommand('help')->setAction(function(\org\octris\cliff\options $options) {
                if (count($operands = $options->getOperands()) != 1) {
                    $this->showUsage();
                    exit(1);
                }
                
                if (!isset($this->commands[$operands[0]])) {
                    printf("octris: '%s' is not a command\n", $operands[0]);
                    exit(1);
                }
                
                $class = '\\octris\\command\\' . $operands[0];
                
                print trim($class::getManual(), "\n") . "\n";
                exit(1);
            });
            
            // create
            $cmd = $opts->addCommand('create')->setAction(function(\org\octris\cliff\options $options) {
                // (new \octris\command\create($args))->run();
                print "subcommand create\n";
                var_dump($options);
            });
            $cmd->addOption(['p'], options::T_VALUE);
            $cmd->addOption(['t'], options::T_VALUE);
            $cmd->addOption(['d'], options::T_KEYVALUE);

            if (!$opts->process()) {
                $this->showUsage();
                exit(1);
            }
            
            //
            //
            // switch ($arg) {
            //     case '--help':
            //     case '-h':
            //         $this->showUsage();
            //         exit(1);
            //     case '--version':
            //         printf("octris %s (%s)\n", self::T_VERSION, self::T_VERSION_DATE);
            //         exit(1);
            //     case 'help':
            //         $help = true;
            //         $arg  = array_shift($argv);
            //         /** FALL THRU **/
            //     default:
            //         if (!(preg_match('/^[a-z]+$/', $arg))) {
            //             $this->showUsage();
            //             exit(1);
            //         } elseif (!isset($this->commands[$arg])) {
            //             printf("octris: '%s' is not a command\n", $arg);
            //             exit(1);
            //         } else {
            //             $class = "\\octris\\command\\$arg";
            //
            //             if ($help) {
            //                 print trim($class::getManual(), "\n") . "\n";
            //                 exit(1);
            //             } else {
            //                 provider::set('args', $this->getOptions($argv));
            //
            //                 $instance = new $class();
            //
            //                 if (($return = $instance->run())) {
            //                     printf("**error** %s\n", rtrim($instance->getError(), "\n"));
            //
            //                     exit($return);
            //                 }
            //             }
            //         }
            // }

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
            debug_print_backtrace();
            
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
                if (($desc = $class::getDescription()) == '') {
                    continue;
                }
                
                printf("    %-" . $size . "s    %s\n", $command, $class::getDescription());
            }
        }
    }

    provider::set('env', $_ENV);
}
