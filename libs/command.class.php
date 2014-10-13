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
     * Command base class.
     *
     * @octdoc      c:libs/command
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    abstract class command
    /**/
    {
        /**
         * Name of command.
         *
         * @octdoc  p:command/$name
         * @type    string
         */
        private $name = 'unknown';
        /**/
        
        /**
         * Error message.
         *
         * @octdoc  p:command/$error
         * @type    string
         */
        protected $error = '';
        /**/
        
        /**
         * Arguments for command.
         *
         * @octdoc  p:command/$args
         * @type    \org\octris\cliff\options\collection
         */
        protected $args;
        /**/
        
        /**
         * Constructor.
         *
         * @octdoc  m:command/__construct
         * @param   \org\octris\cliff\options\collection    $args       Arguments for command.
         */
        public function __construct(\org\octris\cliff\options\collection $args)
        /**/
        {
            $this->name = (new \ReflectionClass($this))->getShortName();
            $this->args = $args;
        }
        
        /**
         * Return name of command.
         *
         * @octdoc  m:command/getName
         * @return  string                                  Name of command.
         */
        public function getName()
        /**/
        {
            return $this->name;
        }
        
        /**
         * Return command description.
         *
         * @octdoc  m:command/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'No description available.';
        }
        
        /**
         * Return command manual.
         *
         * @octdoc  m:command/getManual
         */
        public static function getManual()
        /**/
        {
            return 'No additional help available for command.';
        }

        /**
         * Return error message.
         *
         * @octdoc  m:command/getError
         * @return  string                                          Error message.
         */
        public function getError()
        /**/
        {
            return $this->error;
        }

        /**
         * Set error message.
         *
         * @octdoc  m:command/setError
         */
        protected function setError($msg)
        /**/
        {
            $this->error = $msg;
        }

        /**
         * Get a file iterator for a specified directory and specified regular expression matching file names.
         *
         * @octdoc  m:command/getIterator
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
         * @octdoc  m:command/run
         */
        abstract public function run();
        /**/
    }
}
