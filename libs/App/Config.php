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
class Config implements \Octris\Cli\App\CommandInterface
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
        $command->setHelp('List and change configuration.');
        $command->setDescription('This command lists or changes the local or global configuration of the OCTRiS command-line tool. The local configuration is 
located in the parent directory your projects are created in. The global configuration is located in the home-directory.');
        $command->setExample(<<<EXAMPLE
Change company name in configuration:

    $ ./octris config -s info -d company=foo
EXAMPLE
        );
        $command->addOption('section', '-s | --section <section>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'Section to list or to set value in.',
            'required' => true
        ]);
        $command->addOption('local', '-l | --local <path>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'Set configuration local instead of globel.'
        ])->addValidator(function($value) {
            return is_dir($value);
        }, 'Specified path is not a directory or directory not found');
        $command->addOption('key-value', '-d | --define <key-value>', ['\Aaparser\Coercion', 'kv'], [
            'help' => 'Set a configuration value in the form of key=value.'
        ])->addValidator(function($value) {
            var_dump($value);
            return true;
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
        // handle project configuration
        $cfg = new \Octris\Cliconfig(['/etc']);
        $cfg->load(\Octris\Cliconfig::getHome() . '/.octris.ini');

        var_dump($cfg);
        die;
        
        // $cfg->load($dir . '/.octris.ini');

        $data = [];
        $info = $cfg->addSection('info');

        if (isset($options['key-value'])) {
            foreach ($options['key-value'] as $k => $v) {
                $info[$k] = $v;
            }
        }

        // foreach (self::$settings as $k) {
        //     $info[$k] = readline::getPrompt(
        //         sprintf(
        //             '%s [%%s]: ',
        //             $k
        //         ),
        //         (isset($info[$k]) ? $info[$k] : '')
        //     );
        //
        //     $data[$k] = preg_replace('/<package>/', $package, $info[$k]);
        // }

        print "\n";

        if ($cfg->hasChanged()) {
            do {
                $yn = readline::getPrompt('Save changed configuration? (Y/n) ', 'y');
            } while (!preg_match('/^[YyNn]$/', $yn));
            
            if ($yn == 'y') {
                $cfg->save();
            }
            
            print "\n";
        }

        // $prj = new \Octris\Core\Config('global');
        //
        // if (isset($options['key-value'])) {
        //     foreach ($options['key-value'] as $k => $v) {
        //         $prj['info.' . $k] = $v;
        //     }
        // }
        //
        // $filter = $prj->filter('info');
        //
        // foreach ($filter as $k => $v) {
        //     printf("%-10s%s\n", $k, $v);
        // }
        //
        // $prj->save();
    }
}
