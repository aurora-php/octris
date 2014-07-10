<?php

/*
 * This file is part of the '{{$vendor}}/{{$module}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}}\Models;

/**
 * Proxy for accessing different database backends.
 *
 * @octdoc      c:models/database
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
class Database
{
    /**
     * Instance of database backend class.
     *
     * @octdoc  p:database/$backend
     * @type    \{{$namespace}}\Models\Database
     */
    protected $backend;
    /**/

    /**
     * Constructor.
     *
     * @octdoc  m:database/__construct
     * @param   array           $settings                   Database connection settings.
     */
    public function __construct(array $settings)
    {
        $class = get_class($this);
        $class = substr($class, strrpos($class, '\\'));
        $class = '{{$namespace}}\Models\\' . $settings['device'] . $class;

        $this->backend = new $class($settings);
    }
}
