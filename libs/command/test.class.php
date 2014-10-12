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

    /**
     * Execute phpunit test-suite for a project.
     *
     * @octdoc      c:command/test
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class test extends \octris\command
    /**/
    {
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
         * @octdoc  m:create/getManual
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
         */
        public function run()
        /**/
        {
            // import required parameters
            $args = provider::access('args');

            if ($args->isExist(0) && $args->isValid(0, validate::T_PATH)) {
                $dir = $args->getValue(0) . '/tests/';
                
                if (!is_dir($dir)) {
                    $this->setError('no tests available');
                    
                    return 1;
                }
            } else {
                $this->setError('specified path is not a directory or directory not found');
                
                return 1;
            }
            
            if ($args->isExist('f') && $args->isValid('f', validate::T_PRINTABLE)) {
                $filter = '--filter ' . escapeshellarg($args->getValue('f'));
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
