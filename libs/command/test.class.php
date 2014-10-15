<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace octris\command {
    use \org\octris\core\provider as provider;
    use \org\octris\core\validate as validate;
    use \org\octris\cliff\options as options;

    /**
     * Execute phpunit test-suite for a project.
     *
     * @octdoc      c:command/test
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class test extends \org\octris\cliff\app\command
    /**/
    {
        /**
         * Constructor.
         *
         * @octdoc  m:test/__construct
         */
        public function __construct()
        /**/
        {
            parent::__construct();
        }
        
        /**
         * Configure command arguments.
         *
         * @octdoc  m:test/configure
         */
        public function configure()
        /**/
        {
            $this->addOption(['f', 'filter'], options::T_VALUE)->setValidator(function($value) {
                $validator = new \org\octris\core\validate\type\printable();
                return $validator->validate($validator->preFilter($value));
            }, 'invalid filter specified');
        }
        
        /**
         * Return command description.
         *
         * @octdoc  m:test/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Execute phpunit tests.';
        }

        /**
         * Return command manual.
         *
         * @octdoc  m:test/getManual
         */
        public static function getManual()
        /**/
        {
            return <<<EOT
NAME
    octris test - execute phpunit tests.
    
SYNOPSIS
    octris test     [-f filter]
                    <project-path>
    
DESCRIPTION
    This command is used to execute the phpunit test-suite of a
    project, if available.
    
OPTIONS
    -f      Filter to apply.

EXAMPLES
    Validate a project:
    
        $ ./octris test ~/tmp/org.octris.test
EOT;
        }

        /**
         * Run command.
         *
         * @octdoc  m:test/run
         * @param   \org\octris\cliff\options\collection        $args           Parsed arguments for command.
         */
        public function run(\org\octris\cliff\options\collection $args)
        /**/
        {
            if (!isset($args[0])) {
                $this->setError(sprintf("no destination path specified"));
                
                return false;
            } elseif (!is_dir($args[0])) {
                $this->setError('specified path is not a directory or directory not found');
                
                return false;
            } else {
                $dir = $args[0] . '/tests/';
                
                if (!is_dir($dir)) {
                    $this->setError('no tests available');
                    
                    return false;
                }
            }
            
            if (isset($args['f'])) {
                $filter = '--filter ' . escapeshellarg($args['f']);
            } else {
                $filter = '';
            }
            
            if (!($cmd = `which phpunit`)) {
                $this->setError('phpunit not found');
                
                return 1;
            }
            
            // execute tests
            $cmd .= ' --tap ' . $filter . ' ' . $dir;
            
            passthru($cmd);
        }
    }
}
