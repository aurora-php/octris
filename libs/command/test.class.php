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
    /**
     * Test command.
     *
     * @octdoc      c:command/test
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class test extends \octris\command
    /**/
    {
        /**
         * Run command.
         *
         * @octdoc  m:test/run
         */
        public function run()
        /**/
        {
            return 0;
        }
    }
}