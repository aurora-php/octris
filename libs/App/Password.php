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
 * Password related tools.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Password implements \Octris\Cli\App\ICommand
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get algorithm constant value for name.
     *
     * @param   string          $name                       Name of algorithm.
     * @return  string|int                                  Algorithm constant value.
     */
    protected static function getAlgoConstant($name)
    {
        $algos = \Octris\Core\Security\Password::getAlgorithms();

        $return = 0;

        foreach ($algos as $algo) {
            if ($algo['name'] == $name) {
                $return = $algo['algo'];
                break;
            }
        }

        return $return;
    }

    /**
     * Create a password hash.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    protected static function createHash(array $options, array $operands)
    {
        $algo = (isset($options['algo'])
                    ? self::getAlgoConstant($options['algo'])
                    : PASSWORD_DEFAULT);

        $hash = \Octris\Core\Security\Password::hash($operands['password'][0], $algo, $options);

        print "$hash\n";
    }

    /**
     * List supported algorithms.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    protected static function listAlgorithms(array $options, array $operands)
    {
        $algos = \Octris\Core\Security\Password::getAlgorithms();

        foreach ($algos as $algo) {
            print ($algo['is_default'] ? ' * ' : '   ') . $algo['name'] . "\n";
        }
    }

    /**
     * List supported algorithms.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    protected static function verifyHash(array $options, array $operands)
    {
        $is_valid = \Octris\Core\Security\Password::verify($operands['password'][0], $operands['hash'][0]);

        print ($is_valid ? 'OK' : 'ERROR') . "\n";
    }

    /**
     * Display password hash details.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    protected static function infoHash(array $options, array $operands)
    {
        $info = \Octris\Core\Security\Password::getInfo($operands['hash'][0]);

        $out = [['name' => 'algo', 'value' => $info['algoName']]];
        $max = 4;

        foreach ($info['options'] as $k => $v) {
            $out[] = ['name' => $k, 'value' => $v];
            $max = max(strlen($k), $max);
        }

        foreach ($out as $_out) {
            printf('%-' . $max . "s: %s\n", $_out['name'], $_out['value']);
        }
    }

    /**
     * Configure the command.
     *
     * @param   \Aaparser\Command       $command            Instance of an aaparser command to configure.
     */
    public static function configure(\Aaparser\Command $command)
    {
        $command->setHelp('Create and verify password hashes.');

        $command->addCommand('list', [
            'help' => 'List supported algorithms.'
        ])->setAction(function(array $options, array $operands) {
            self::listAlgorithms($options, $operands);
        });

        $cmd = $command->addCommand('info', [
            'help' => 'Display password hash details.'
        ])->setAction(function(array $options, array $operands) {
            self::infoHash($options, $operands);
        });
        $cmd->addOperand('hash', 1, [
            'help' => 'Hash to display information for.'
        ]);

        $cmd = $command->addCommand('verify', [
            'help' => 'Verify password.'
        ])->setAction(function(array $options, array $operands) {
            self::verifyHash($options, $operands);
        });
        $cmd->addOperand('password', 1, [
            'help' => 'Password to verify.'
        ]);
        $cmd->addOperand('hash', 1, [
            'help' => 'Hash to use for verification.'
        ]);

        $cmd = $command->addCommand('create', [
            'help' => 'Create a password hash.'
        ])->setAction(function(array $options, array $operands) {
            self::createHash($options, $operands);
        });
        $cmd->addOption('algo', '-a | --algo <algo>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'The algorithm to use.'
        ])->addValidator(function($value) {
            $algos = \Octris\Core\Security\Password::getAlgorithms();
            $names = array_map(
                function($item) {
                    return $item['name'];
                },
                $algos
            );

            return in_array($value, $names);
        }, 'Unknown algorithm specified.');
        $cmd->addOption('cost', '-c | --cost <cost>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'The cost factor to use.'
        ]);
        $cmd->addOption('iterations', '-i | --iterations <iterations>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'The iteration factor to use.'
        ]);
        $cmd->addOperand('password', 1, [
            'help' => 'Password to hash'
        ]);
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
