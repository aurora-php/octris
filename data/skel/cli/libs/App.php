<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
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
class App extends \Octris\Cliff\App
{
    /**
     * Application name.
     *
     * @type    string
     */
    protected static $app_name = '{{$vendor}}-{{$module}}';
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
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute application.
     *
     * @param   \Octris\Cliff\Args\Collection        $args           Parsed arguments.
     */
    protected function main(\Octris\Cliff\Args\Collection $args);
    {
    }
}
