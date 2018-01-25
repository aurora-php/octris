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
 * Execute phpunit test-suite for a project.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Test implements \Octris\Cli\App\CommandInterface
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
        $command->setHelp('Execute phpunit tests.');
        $command->setDescription('This command is used to execute the phpunit test-suite of a project, if available.');
        $command->setExample(<<<EXAMPLE
Run test-suite of a project:

    $ ./octris test ~/tmp/test
EXAMPLE
        );
        $command->addOption('filter', '-f | --filter <filter>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'Filter to apply.'
        ])->addValidator(function($value) {
            $validator = new \Octris\Core\Validate\Type\Printable();

            return $validator->validate($validator->preFilter($value));
        }, 'invalid filter specified');
        $op = $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ])->addValidator(function($value) {
            return !!(`which phpunit`);
        }, 'phpunit not found');
        \Octris\Util\Validator::addProjectPathCheck($op);
        $op->addValidator(function($value) {
            return is_dir($value . '/tests/');
        }, 'no tests available');
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $dir = rtrim($operands['project-path'][0], '/') . '/tests/';

        $filter = (isset($options['filter'])
                    ? '--filter ' . escapeshellarg($options['filter'])
                    : '');

        // execute tests
        $cmd = 'phpunit --tap ' . $filter . ' ' . $dir;

        passthru($cmd);
    }
}
