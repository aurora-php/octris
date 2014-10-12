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
     * Lint a project.
     *
     * @octdoc      c:command/lint
     * @copyright   copyright (c) 2012-2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class lint extends \octris\command
    /**/
    {
        /**
         * Return command description.
         *
         * @octdoc  m:lint/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Validate files in a project.';
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
    octris lint - validate files in a project.
    
SYNOPSIS
    octris lint     <project-path>
    
DESCRIPTION
    This command is used to validate the files in a project. Currently
    validation can be performed for php files and template files.
    
OPTIONS

EXAMPLES
    Validate a project:
    
        $ ./octris lint ~/tmp/org.octris.test
EOT;
        }

        /**
         * Run command.
         *
         * @octdoc  m:lint/run
         */
        public function run()
        /**/
        {
            // import required parameters
            $args = provider::access('args');

            if ($args->isExist(0) && $args->isValid(0, validate::T_PATH)) {
                $dir = $args->getValue(0);
            } else {
                $this->setError('specified path is not a directory or directory not found');
                
                return 1;
            }

            // lint php files
            $iterator = $this->getIterator($dir, '/\.php$/', '/(\/data\/cldr\/)/');

            foreach ($iterator as $filename => $cur) {
                system(PHP_BINARY . ' -l ' . escapeshellarg($filename));
            }

            // lint templates
            if (is_dir($dir . '/templates/')) {
                $iterator = $this->getIterator($dir . '/templates/', '/\.html$/');
                
                $tpl = new \org\octris\core\tpl\lint();

                foreach ($iterator as $filename => $cur) {
                    print $filename . "\n";

                    try {
                        $tpl->process($filename, \org\octris\core\tpl::T_ESC_HTML);
                    } catch(\Exception $e) {
                    }
                }
            }
        }
    }
}
