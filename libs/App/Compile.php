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

/**
 * Template compiler.
 *
 * @copyright   copyright (c) 2015-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Compile implements \Octris\Cli\App\CommandInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Configure the command.
     *
     * @param   \Aaparser\Command       $command            Instance of an aaparser command to configure.
     */
    public static function configure(\Octris\Cli\App\Command $command)
    {
        $command->setHelp('Compile project templates.');
        $command->setDescription('This command is used to compile the project templates.');
        $command->setExample(<<<EXAMPLE
Compile templates of a project:

    $ ./octris compile ~/tmp/test
EXAMPLE
        );
        $op = $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ]);
        \Octris\Util\Validator::addProjectPathCheck($op);
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        global $argv;

        passthru($argv[0] . ' exec compile ' . escapeshellarg($operands['project-path'][0]));
    }
}
