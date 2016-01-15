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
 * Change OCTRiS command-line tool configuration.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Config implements \Octris\Cli\App\ICommand
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
        $command->setHelp('List and change configuration.');
        $command->setDescription('This command lists or changes the global OCTRiS command-line tool configuration.');
        $command->setExample(<<<EXAMPLE
Change company name in configuration:

    $ ./octris config -s company=foo
EXAMPLE
        );
        $command->addOption('key-value', '-s | --set <key-value>', ['\Aaparser\Coercion', 'kv'], [
            'help' => 'Set a configuration value in the form of key=value. Allowed keys are: company, author and email.'
        ])->addValidator(function($value) {
            return in_array(key($value), array('company', 'author', 'email'));
        }, 'Invalid configuration key specified "${value}"')
          ->addValidator(function($value) {
            $val = current($value);

            return (!is_null($val) && $val !== '');
        }, 'Configuration value must not be empty');
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $prj = new \Octris\Core\Config('global');

        if (isset($options['key-value'])) {
            foreach ($options['key-value'] as $k => $v) {
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
