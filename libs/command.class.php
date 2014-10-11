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
         * Command arguments.
         *
         * @octdoc  p:command/$args
         * @type    array
         */
        protected $args = array();
        /**/
        
        /**
         * Name of command.
         *
         * @octdoc  p:command/$name
         * @type    string
         */
        private $name = 'unknown';
        /**/
        
        /**
         * Constructor.
         *
         * @octdoc  m:command/__construct
         * @param   array               $args               Command arguments.
         */
        public function __construct(array $args)
        /**/
        {
            $this->args = $args;
            $this->name = (new \ReflectionClass($this))->getShortName();
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
         * Show usage information.
         *
         * @octdoc  m:command/showUsage
         */
        public function showUsage()
        /**/
        {
            printf("no additional help available for command '%s'.\n", $this->getName());
        }

        /**
         * Run command.
         *
         * @octdoc  m:command/run
         */
        public function run()
        /**/
        {
            return 0;
        }
    }
}
