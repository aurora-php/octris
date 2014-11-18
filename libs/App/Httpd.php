<?php

/*
 * This file is part of the octris/octris.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris\App;

use \Octris\Core\Provider as provider;
use \Octris\Core\Validate as validate;
use \Octris\Cliff\Args    as args;

/**
 * Start HTTPD backend.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Httpd extends \Octris\Cliff\Args\Command
{
    /**
     * Constructor.
     *
     * @param   string                              $name               Name of command.
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * Configure command arguments.
     */
    public function configure()
    {
        $this->addOption(['f', 'filter'], args::T_VALUE)->addValidator(function ($value) {
            $validator = new \Octris\Core\Validate\Type\Printable();
            return $validator->validate($validator->preFilter($value));
        }, 'invalid filter specified');
    }

    /**
     * Return command description.
     */
    public static function getDescription()
    {
        return 'Httpd backend.';
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return <<<EOT
NAME
    octris httpd - start http backend for testing project.

SYNOPSIS
    octris httpd

DESCRIPTION
    This command uses PHP's builtin webserver for testing a project.

OPTIONS

EXAMPLES
    Example:

        $ ./octris httpd
EOT;
    }

    /**
     * Run command.
     *
     * @param   \Octris\Cliff\Args\Collection        $args           Parsed arguments for command.
     */
    public function run(\Octris\Cliff\Args\Collection $args)
    {
    }
}
