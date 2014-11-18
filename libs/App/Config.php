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

/**
 * Change OCTRiS command-line tool configuration.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Config extends \Octris\Cliff\Args\Command
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
     * Return command description.
     */
    public static function getDescription()
    {
        return 'Change configuration.';
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return <<<EOT
NAME

SYNOPSIS
    octris config   [-s company=<company-name> |
                     -s author=<author-name> |
                     -s email=<email-address>]

DESCRIPTION
    This command changes the OCTRIS command-line tool configuration.

OPTIONS

EXAMPLES
    Change company name in configuration:

        $ ./octris config -s company=foo
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
