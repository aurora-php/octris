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
     * Documentation tools.
     *
     * @octdoc      c:command/doc
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class doc extends \org\octris\cliff\app\command
    /**/
    {
        /**
         * Constructor.
         *
         * @octdoc  m:doc/__construct
         */
        public function __construct()
        /**/
        {
            parent::__construct();
        }
        
        /**
         * Return command description.
         *
         * @octdoc  m:doc/getDescription
         */
        public static function getDescription()
        /**/
        {
            return '';
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
            // $this->setError('not implemented, yet');
            //
            // return 1;

            // export EBNF
            $grammar = new \org\octris\core\tpl\compiler\grammar();
            print $grammar->getEBNF();
        }
    }
}
