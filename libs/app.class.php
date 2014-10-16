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
    use \org\octris\core\provider as provider;

    /**
     * Application class.
     *
     * @octdoc      c:libs/app
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class app extends \org\octris\cliff\app
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
        protected static $app_version = '0.0.4';
        /**/
        
        /**
         * Application version date.
         *
         * @octdoc  p:app/$app_version_date
         * @type    string
         */
        protected static $app_version_date = '2014-10-15';
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
            
            $this->addCommand(new \octris\command\create('create'));
            $this->addCommand(new \octris\command\graph('graph'));
            $this->addCommand(new \octris\command\lint('lint'));
            $this->addCommand(new \octris\command\test('test'));
            $this->addCommand(new \octris\command\doc('doc'));
        }

        /**
         * Run main application.
         *
         * @octdoc  m:app/main
         * @param   \org\octris\cliff\args\collection        $args           Parsed arguments.
         */
        protected function main(\org\octris\cliff\args\collection $args)
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
