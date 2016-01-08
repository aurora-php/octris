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
 * Documentation tools.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Doc implements \Octris\Cli\App\ICommand
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
    public static function configure(\Aaparser\Command $command)
    {
        $command->setHelp('Display documentation for various framework parts. Display documentation for various framework parts. Display documentation for various framework parts. Display documentation for various framework parts.');

        $command->addCommand('ebnf', [
            'help' => 'Display EBNF of template grammar.'
        ])->setAction(function(array $options, array $operands) {
            $grammar = new \Octris\Core\Tpl\Compiler\Grammar();
            print $grammar->getEBNF();
        });
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
    }
}
