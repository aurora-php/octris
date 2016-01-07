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

use \Octris\Readline as readline;

/**
 * Create a new project.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Create implements \Octris\Cli\App\ICommand
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
        $package = '';

        $command->setHelp('Create a new project.');
        $command->addOption('project', '-p | --project <project-name>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'A valid name for the project in the form of <vendor>/<package>.',
            'required' => true
        ])->addValidator(function($value) use (&$package) {
            $package = $value;

            $validator = new \Octris\Core\Validate\Type\Project();

            if (($is_valid = $validator->validate($validator->preFilter($value)))) {
                list(, $package) = explode('/', $value);
            }

            return $is_valid;
        }, 'invalid project name specified');
        $command->addOption('type', '-t | --type <project-type>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'A project type. Valid types are "web", "cli" and "lib".',
            'required' => true
        ])->addValidator(function($value) {
            return in_array($value, ['web', 'cli', 'lib']);
        }, 'invalid project type specified')
          ->addValidator(function($value) {
            return is_dir(__DIR__ . '/../../data/skel/' . $value . '/');
        }, 'unable to locate template directory "' . __DIR__ . '/../../data/skel/${value}/".');
        $command->addOption('define', '-d | --define <key-value>', ['\Aaparser\Coercion', 'kv'], [
            'help' => 'Overwrite default configuration settings for "company", "author" and "email".'
        ])->addValidator(function($value) {
            return in_array(key($value), array('company', 'author', 'email'));
        }, 'Invalid configuration key specified "${value}"')
          ->addValidator(function($value) {
            $val = current($value);

            return (!is_null($val) && $val !== '');
        }, 'Configuration value must not be empty');
        $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ])->addValidator(function($value) {
            return is_dir($value);
        }, 'specified path is not a directory or directory not found')
          ->addValidator(function($value) use (&$package) {
            $package_dir = rtrim($value, '/') . '/' . $package;

            return !is_dir($package_dir);
        }, 'project directory already exists');
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return <<<EOT
NAME
    octris create - create a new project.

SYNOPSIS
    octris create   -p <project-name>
                    -t web | cli | lib
                    [-d info.company=<company-name>]
                    [-d info.author=<author-name>]
                    [-d info.email=<author-email>]
                    <destination-path>

DESCRIPTION
    This command creates a new project of specified type in the specified
    destination-path. A valid basic directory layout will be created from
    a skeleton according to the specified project-type.

    The current supported types are 'web', 'cli' and 'lib':

    web     This type should be used for projects like web applications,
            web sites etc.
    cli     This type should be used for command-line applications.
    lib     This type should be used for (shared) libraries.

OPTIONS
    -p      A valid name for the project in the form of <vendor>/<package>.

    -t      A valid type for the project

    -d      Additional definitions to set that will be used to rewrite
            comments in the project skeleton. The current supported fields
            that can be set are: 'info.company', 'info.author' and
            'info.email'.

EXAMPLES
    Create a test project:

        $ ./octris create -p example/test -t web \
                -d info.company="Foo Inc." \
                -d info.author="Bar Baz" \
                -d info.email="baz@example.org" \
                ~/tmp/
EOT;
    }

    /**
     * Helper method to test whether a file is binary or text file.
     *
     * @param   string          $file               File to test.
     * @param   string          $size               Optional block size to test.
     * @return  bool                                Returns true for binaries.
     */
    protected function isBinary($file, $size = 2048)
    {
        $return = false;

        if (is_file($file) && is_readable($file) && ($fp = fopen($file, 'r'))) {
            $blk = fread($fp, $size);
            fclose($fp);

            $return = (substr_count($blk, "\x00") > 0);
        }

        return $return;
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $project = $options['project'];
        $type = $options['type'];

        list($vendor, $package) = explode('/', $project);

        $year = date('Y');

        // handle project configuration
        $data = array();
        $prj = new \Octris\Core\Config('global');

        if (isset($options['key-value'])) {
            foreach ($options['key-value'] as $k => $v) {
                $prj['info.' . $k] = $v;
            }
        }

        $filter = $prj->filter('info');

        foreach ($filter as $k => $v) {
            $data[$k] = readline::getPrompt(sprintf("%s [%%s]: ", $k), $v);
        }

        print "\n";

        // build data array
        $data = array_merge($data, array(
            'year'      => $year,
            'package'   => $package,
            'vendor'    => $vendor,
            'namespace' => ucfirst($vendor) . '\\' . ucfirst($package),
            'directory' => $vendor . '.' . $package
        ));

        // create project
        $dir = rtrim($operands['project-path'][0], '/') . '/' . $package;
        $src = __DIR__ . '/../../data/skel/' . $type . '/';

        // process skeleton and write project files
        $tpl = new \Octris\Core\Tpl();
        $tpl->addSearchPath($src);
        $tpl->setValues($data);

        $len = strlen($src);

        mkdir($dir, 0755);

        $directories = array();
        $iterator    = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $filename => $cur) {
            $rel   = substr($filename, $len);
            $dst   = $dir . '/' . $rel;
            $path  = dirname($dst);
            $base  = basename($filename);
            $ext   = preg_replace('/^\.?[^\.]+?(\..+|)$/', '\1', $base);
            $base  = basename($filename, $ext);

            if (substr($base, 0, 1) == '$' && isset($data[$base = ltrim($base, '$')])) {
                // resolve variable in filename
                $dst = $path . '/' . $data[$base] . $ext;
            }

            if (!is_dir($path)) {
                // create destination directory
                mkdir($path, 0755, true);
            }

            if (!$this->isBinary($filename)) {
                $cmp = $tpl->fetch($rel, \Octris\Core\Tpl::ESC_NONE);

                file_put_contents($dst, $cmp);
            } else {
                copy($filename, $dst);
            }

            chmod($dst, 0644);
        }

        // reminder
        print "Project created in '$dir'.\n\n";

        print "Next steps you should do:\n";
        print "- edit the 'composer.json' configuration located in the project directory.\n";
        print "- run 'composer update' in the project directory to load dependencies.\n\n";
    }
}
