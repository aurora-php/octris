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
 * Check a project for various kind of coding-style related flaws.
 *
 * @copyright   copyright (c) 2012-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Check implements \Octris\Cli\App\ICommand
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
        $command->setHelp('Syntactical check of project files.');
        $command->setDescription('This command is used to check the syntax of files in a project. Currently validation can be performed for php files and OCTRiS template files.');
        $command->setExample(<<<EXAMPLE
Check a project:

    $ ./octris check ~/tmp/octris/test
EXAMPLE
        );
        $op = $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ]);
        \Octris\Util\Validator::addProjectPathCheck($op);
    }

    /**
     * Get a file iterator for a specified directory and specified regular expression matching file names.
     *
     * @param   string                          $dir            Director to iterate recusrivly.
     * @param   string                          $regexp         Regular expression each file has to match to.
     * @param   string                          $exclude        Optional pattern for filtering files.
     * @return  \RegexIterator                                  The iterator.
     */
    protected function getIterator($dir, $regexp, $exclude = null)
    {
        $iterator = new \RegexIterator(
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)),
            $regexp,
            \RegexIterator::GET_MATCH
        );

        if (!is_null($exclude)) {
            $iterator = new \Octris\Core\Type\Filteriterator($iterator, function ($current, $filename) use ($exclude) {
                return !preg_match($exclude, $filename);
            });
        }

        return $iterator;
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $base = rtrim($operands['project-path'][0], '/');

        // check php files
        $iterator = $this->getIterator($base, '/\.php$/', '/(\/data\/cldr\/|\/vendor\/)/');

        foreach ($iterator as $filename => $cur) {
            system(PHP_BINARY . ' -l ' . escapeshellarg($filename));
        }

        // check templates
        passthru(__DIR__ . '/../../bin/lint.php ' . escapeshellarg($base));
    }
}
