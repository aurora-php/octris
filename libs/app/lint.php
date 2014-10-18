<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace octris\app {
    use \org\octris\core\provider as provider;
    use \org\octris\core\validate as validate;

    /**
     * Lint a project.
     *
     * @octdoc      c:app/lint
     * @copyright   copyright (c) 2012-2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class lint extends \org\octris\cliff\args\command
    /**/
    {
        /**
         * Constructor.
         *
         * @octdoc  m:lint/__construct
         * @param   string                              $name               Name of command.
         */
        public function __construct($name)
        /**/
        {
            parent::__construct($name);
        }
        
        /**
         * Return command description.
         *
         * @octdoc  m:lint/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Syntactical check of project files.';
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
    octris lint - syntactical check of project files.
    
SYNOPSIS
    octris lint     <project-path>
    
DESCRIPTION
    This command is used to check the syntax of files in a project. Currently
    validation can be performed for php files and OCTRiS template files.
    
OPTIONS

EXAMPLES
    Check a project:
    
        $ ./octris lint ~/tmp/org.octris.test
EOT;
        }

        /**
         * Get a file iterator for a specified directory and specified regular expression matching file names.
         *
         * @octdoc  m:lint/getIterator
         * @param   string                          $dir            Director to iterate recusrivly.
         * @param   string                          $regexp         Regular expression each file has to match to.
         * @param   string                          $exclude        Optional pattern for filtering files.
         * @return  \RegexIterator                                  The iterator.
         */
        protected function getIterator($dir, $regexp, $exclude = null)
        /**/
        {
            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)),
                $regexp,
                \RegexIterator::GET_MATCH
            );

            if (!is_null($exclude)) {
                $iterator = new \org\octris\core\type\filteriterator($iterator, function($current, $filename) use ($exclude) {
                    return !preg_match($exclude, $filename);
                });
            }

            return $iterator;
        }

        /**
         * Run command.
         *
         * @octdoc  m:lint/run
         * @param   \org\octris\cliff\args\collection        $args           Parsed arguments for command.
         */
        public function run(\org\octris\cliff\args\collection $args)
        /**/
        {
            if (!isset($args[0])) {
                throw new \org\octris\cliff\exception\argument(sprintf("no project path specified"));
            } elseif (!is_dir($args[0])) {
                throw new \org\octris\cliff\exception\argument('specified path is not a directory or directory not found');
            } else {
                $dir = rtrim($args[0], '/');
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
