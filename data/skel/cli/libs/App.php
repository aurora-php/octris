<?php

/*
 * This file is part of the '{{$vendor}}/{{$package}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}};

/**
 * Application class.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
class App extends \Octris\Cli\App
{
    /**
     * Application name.
     *
     * @type    string
     */
    protected static $app_name = '{{$vendor}}-{{$package}}';
    /**/

    /**
     * Application version.
     *
     * @type    string
     */
    protected static $app_version = '0.0.0';
    /**/

    /**
     * Application version date.
     *
     * @type    string
     */
    protected static $app_version_date = '0000-00-00';
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
     * App initialization, set default action.
     */
    protected function initialize()
    {
        parent::initialize();
    }
}
