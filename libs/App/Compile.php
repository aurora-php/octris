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
 * Template compiler.
 *
 * @copyright   copyright (c) 2015 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Compile extends \Octris\Cliff\Args\Command implements \Octris\Cliff\Args\IManual
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
        $this->addOperand(1, 'project-path')->addValidator(function ($value) {
            return (is_dir($value) && is_dir($value . '/host'));
        }, 'specified path is not a directory or directory not found or directory contains no "host" directory and therefore is probably not an octris web project')->setHelp('Path to a project.');
    }

    /**
     * Return command description.
     */
    public static function getDescription()
    {
        return 'Template compiler.';
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return <<<EOT
NAME
    octris compile - compile project templates.

SYNOPSIS
    octris compile  <project-path>

DESCRIPTION
    This command is used to compile the project templates.

OPTIONS

EXAMPLES
    Example:

        $ ./octris compile ~/tmp/test
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
            throw new \Octris\Cliff\Exception\Argument('no project path specified');
        } elseif (!is_dir($args[0])) {
            throw new \Octris\Cliff\Exception\Argument('specified path is not a directory or directory not found');
        }

        $base = rtrim($args[0], '/');

        if (!is_file($base . '/etc/global.php')) {
            throw new \Octris\Cliff\Exception\Argument(sprintf('global app configuration not found "%s"!', $base . '/etc/global.php'));
        }

        passthru(__DIR__ . '/../../bin/compile.php ' . escapeshellarg($base));
    }
}
