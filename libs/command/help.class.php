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
     * Show command help.
     *
     * @octdoc      c:command/help
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class help extends \octris\command
    /**/
    {
        /**
         * Return command description.
         *
         * @octdoc  m:command/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Show manual page for a command.';
        }
    }
}
