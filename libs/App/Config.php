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
use \Octris\Cliff\Args as args;

/**
 * Change OCTRiS command-line tool configuration.
 *
 * @copyright   copyright (c) 2014-2015 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Config extends \Octris\Cliff\Args\Command implements \Octris\Cliff\Args\IManual
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
        $this->addOption(['s', 'set'], args::T_KEYVALUE)->addValidator(function ($value, $key) {
            return (in_array($key, ['company', 'author', 'email']) && $value != '');
        }, 'invalid argument value');
    }

    /**
     * Return command description.
     */
    public static function getDescription()
    {
        return 'List and change configuration.';
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
    This command lists or changes the global OCTRIS command-line tool
    configuration.

OPTIONS
    -s      Key-value pairs of global configuration settings. Current
            supported settings are:

            company
            author
            email

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
        $prj = new \Octris\Core\Config('global');

        if (isset($args['set'])) {
            foreach ($args['set'] as $k => $v) {
                $prj['info.' . $k] = $v;
            }
        }

        $filter = $prj->filter('info');

        foreach ($filter as $k => $v) {
            printf("%-10s%s\n", $k, $v);
        }

        $prj->save();
    }
}
