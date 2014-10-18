<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}} {
    /**
     * Application class.
     *
     * @octdoc      c:libs/app
     * @copyright   copyright (c) {{$year}} by {{$company}}
     * @author      {{$author}} <{{$email}}>
     */
    class app extends \octris\cliff\app
    /**/
    {
        /**
         * Application name.
         *
         * @octdoc  p:app/$app_name
         * @type    string
         */
        protected static $app_name = '{{$vendor}}-{{$module}}';
        /**/
        
        /**
         * Application version.
         *
         * @octdoc  p:app/$app_version
         * @type    string
         */
        protected static $app_version = '0.0.0';
        /**/
        
        /**
         * Application version date.
         *
         * @octdoc  p:app/$app_version_date
         * @type    string
         */
        protected static $app_version_date = '0000-00-00';
        /**/
        
        /**
         * Constructor.
         *
         * @octdoc  m:app/__construct
         */
        public function __construct()
        /**/
        {
        }

        /**
         * Execute application.
         *
         * @octdoc  m:app/main
         * @param   \octris\cliff\args\collection        $args           Parsed arguments.
         */
        protected function main(\octris\cliff\args\collection $args);
        /**/
        {
        }
    }
}

