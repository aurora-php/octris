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
     * Application class.
     *
     * @octdoc      c:libs/app
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class app extends \org\octris\cliff\app
    /**/
    {
        /**
         * Application name.
         *
         * @octdoc  p:app/$app_name
         * @type    string
         */
        protected static $app_name = 'octris';
        /**/
        
        /**
         * Application version.
         *
         * @octdoc  p:app/$app_version
         * @type    string
         */
        protected static $app_version = '0.0.3';
        /**/
        
        /**
         * Application version date.
         *
         * @octdoc  p:app/$app_version_date
         * @type    string
         */
        protected static $app_version_date = '2014-10-13';
        /**/
        
        /**
         * Available commands.
         *
         * @octdoc  p:app/$commands
         * @type    array
         */
        protected $commands = array();
        /**/

        /**
         * Constructor.
         *
         * @octdoc  m:app/__construct
         * @param   
         */
        public function __construct()
        /**/
        {
        }

        /**
         * Determine available "commands".
         *
         * @octdoc  m:app/getCommands
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
         * @octdoc  m:app/main
         * @param   \org\octris\cliff\options\collection        $args           Parsed arguments.
         */
        public function main(\org\octris\cliff\options\collection $args)
        /**/
        {
            // $this->getCommands();
            //
            // $opts = new \org\octris\cliff\options();
            // $opts->addOption(['h', 'help'])->setAction(function() {
            //     $this->showUsage();
            //     exit(1);
            // });
            // $opts->addOption(['version'])->setAction(function() {
            //     printf("octris %s (%s)\n", self::T_VERSION, self::T_VERSION_DATE);
            //     exit(1);
            // });
            //
            // // help command
            // $opts->addCommand('help')->setAction(function(\org\octris\cliff\options\collection $collection) {
            //     if (count($operands = $collection->getOperands()) != 1) {
            //         $this->showUsage();
            //         exit(1);
            //     }
            //
            //     if (!isset($this->commands[$operands[0]])) {
            //         printf("octris: '%s' is not a command\n", $operands[0]);
            //         exit(1);
            //     }
            //
            //     $class = '\\octris\\command\\' . $operands[0];
            //
            //     print trim($class::getManual(), "\n") . "\n";
            //     exit(1);
            // });
            //
            // // create
            // $cmd = $opts->addCommand('create')->setAction(function(\org\octris\cliff\options\collection $collection) {
            //     $cmd = new \octris\command\create($collection);
            //
            //     if (($return = $cmd->run())) {
            //         printf("**error** %s\n", rtrim($cmd->getError(), "\n"));
            //         exit(1);
            //     }
            // });
            // $cmd->addOption(['p', 'project'], options::T_VALUE | options::T_REQUIRED)->setValidator(function($value) {
            //     $validator = new \org\octris\core\validate\type\project();
            //     return $validator->validate($validator->preFilter($value));
            // }, 'invalid project name specified');
            // $cmd->addOption(['t', 'type'], options::T_VALUE | options::T_REQUIRED)->setValidator(function($value) {
            //     return in_array($value, ['web', 'cli', 'lib']);
            // }, 'invalid project type specified');
            // $cmd->addOption(['d', 'define'], options::T_KEYVALUE)->setValidator(function($value, $key) {
            //     return (in_array($key, ['info.company', 'info.author', 'info.email']) && $value != '');
            // }, 'invalid argument value');
            //
            // if (!$opts->process()) {
            //     exit(1);
            // }

            exit(0);
        }
    
        /**
         * Show help.
         *
         * @octdoc  m:app/showHelp
         */
        public function showHelp()
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
", 'v' . static::$app_version);

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