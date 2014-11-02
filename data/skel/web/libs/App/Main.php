<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}}\App;

/**
 * Main application class. This class is only used to define an entry page - if it's the
 * first request to the web application and therefore no other page (next_page) is specified through the
 * application state, this entry page is required.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
class Main extends \Octris\Core\App\Web
{
    /**
     * Page to use a entry point, if no "next_page" is specified through the
     * application state.
     *
     * @type    string
     */
    protected $entry_page = '{{$namespace}}\App\Entry';
    /**/
}
