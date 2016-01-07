<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris;

/**
 * Application class.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class App extends \Octris\Cli\App
{
    /**
     * Application name.
     *
     * @type    string
     */
    protected static $app_name = 'octris';
    /**/

    /**
     * Application version.
     *
     * @type    string
     */
    protected static $app_version = '0.0.12';
    /**/

    /**
     * Application version date.
     *
     * @type    string
     */
    protected static $app_version_date = '2016-01-03';
    /**/

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            self::$app_name,
            [
                'version' => self::$app_version,
                'version_date' => self::$app_version_date,
                'version_string' => "\${name} \${version} (\${version_date})\n"
            ]
        );
    }

    /**
     * Print help.
     * 
     * @param   \Aaparser\Command       $command                Optional command to print help for.
     */
    public function printHelp(\Aaparser\Command $command = null)
    {
        printf("               __         .__
  ____   _____/  |________|__| ______
 /  _ \_/ ___\   __\_  __ \  |/  ___/    OCTRiS framework tool
(  <_> )  \___|  |  |  | \/  |\___ \     copyright (c) %s by Harald Lapp
 \____/ \___  >__|  |__|  |__/____  >    http://github.com/octris/octris/
            \/%20s\/\n\n",
            explode('-', static::$app_version_date)[0],
            'v' . static::$app_version
        );

        parent::printHelp($command);
    }

    /**
     * App initialization, set default action.
     */
    protected function initialize()
    {
        parent::initialize();
        
        $this->importCommand('check', '\Octris\App\Check');
        $this->importCommand('httpd', '\Octris\App\Httpd');
        // $this->importCommand('config', '\Octris\App\Config');
        // $this->importCommand('create', '\Octris\App\Create');
        // $this->importCommand('graph', '\Octris\App\Graph');
        // $this->importCommand('test', '\Octris\App\Test');
        // $this->importCommand('doc', '\Octris\App\Doc');
        // $this->importCommand('compile', '\Octris\App\Compile');
    }
}
