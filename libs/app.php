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
    use \octris\core\provider as provider;

    /**
     * Application class.
     *
     * @octdoc      c:libs/app
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
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
        protected static $app_name = 'octris';
        /**/
        
        /**
         * Application version.
         *
         * @octdoc  p:app/$app_version
         * @type    string
         */
        protected static $app_version = '0.0.5';
        /**/
        
        /**
         * Application version date.
         *
         * @octdoc  p:app/$app_version_date
         * @type    string
         */
        protected static $app_version_date = '2014-10-18';
        /**/
        
        /**
         * Constructor.
         *
         * @octdoc  m:app/__construct
         * @param   
         */
        public function __construct()
        /**/
        {
            parent::__construct();
        }

        /**
         * Configure application arguments.
         *
         * @octdoc  m:app/configure
         */
        protected function configure()
        /**/
        {
            parent::configure();
            
            $this->addCommand(new \octris\app\create('create'));
            $this->addCommand(new \octris\app\graph('graph'));
            $this->addCommand(new \octris\app\lint('lint'));
            $this->addCommand(new \octris\app\test('test'));
            $this->addCommand(new \octris\app\doc('doc'));
        }

        /**
         * Run main application.
         *
         * @octdoc  m:app/main
         * @param   \octris\cliff\args\collection        $args           Parsed arguments.
         */
        protected function main(\octris\cliff\args\collection $args)
        /**/
        {
            exit(0);
        }
    
        /**
         * Show help.
         *
         * @octdoc  m:app/showHelp
         */
        protected function showHelp()
        /**/
        {
            printf("               __         .__        
  ____   _____/  |________|__| ______
 /  _ \_/ ___\   __\_  __ \  |/  ___/    OCTRiS framework tool
(  <_> )  \___|  |  |  | \/  |\___ \     copyright (c) 2014 by Harald Lapp
 \____/ \___  >__|  |__|  |__/____  >    http://github.com/octris/octris/
            \/%20s\/\n\n", 'v' . static::$app_version);

            parent::showHelp();
        }
    }

    provider::set('env', $_ENV);
}
