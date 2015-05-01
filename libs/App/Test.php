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
 * Execute phpunit test-suite for a project.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Test extends \Octris\Cliff\Args\Command implements \Octris\Cliff\Args\IManual
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
        return 'Execute phpunit tests.';
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return <<<EOT
NAME
    octris test - execute phpunit tests.

SYNOPSIS
    octris test     [-f filter]
                    <project-path>

DESCRIPTION
    This command is used to execute the phpunit test-suite of a
    project, if available.

OPTIONS
    -f      Filter to apply.

EXAMPLES
    Validate a project:

        $ ./octris test ~/tmp/org.octris.test
EOT;
    }

    /**
     * Run command.
     *
     * @param   \Octris\Cliff\Args\Collection        $args           Parsed arguments for command.
     */
    public function run(\Octris\Cliff\Args\Collection $args)
    {
        if (!isset($args[0])) {
            throw new \Octris\Cliff\Exception\Argument(sprintf("no destination path specified"));
        } elseif (!is_dir($args[0])) {
            throw new \Octris\Cliff\Exception\Argument('specified path is not a directory or directory not found');
        } else {
            $dir = $args[0] . '/tests/';

            if (!is_dir($dir)) {
                throw new \Octris\Cliff\Exception\Application('no tests available');
            }
        }

        if (isset($args['f'])) {
            $filter = '--filter ' . escapeshellarg($args['f']);
        } else {
            $filter = '';
        }

        if (!($cmd = `which phpunit`)) {
            throw new \Octris\Cliff\Exception\Application('phpunit not found');
        }

        // execute tests
        $cmd .= ' --tap ' . $filter . ' ' . $dir;

        passthru($cmd);
    }
}
