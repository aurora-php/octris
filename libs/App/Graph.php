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
 * Create a page graph of a project.
 *
 * @copyright   copyright (c) 2011-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Graph implements \Octris\Cli\App\ICommand
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
        $command->setHelp('Create a page graph of a project.');
        $command->setDescription(<<<DESCRIPTION
This command is used to analyze the page flow of a project and to create a graph from it that can be visualized using the dot utility of graphviz.

The generated graph will be printed to stdout and can as such be directly processed by the dot utility.
DESCRIPTION
        );
        $command->setExample(<<<EXAMPLE
Create a graph of a project in PDF format using graphviz:

    $ ./octris graph ~/tmp/octris/test |\
            dot -Tpdf
EXAMPLE
        );
        $op = $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ]);
        \Octris\Util\Validator::addWebProjectPathCheck($op);
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $dir = rtrim($operands['project-path'][0], '/');

        $project = basename($dir);
        $ns = str_replace('.', '\\', $project) . '\\';

        /*
         * install new project-specific autoloader
         */
        foreach (spl_autoload_functions() as $autoloader) {
            spl_autoload_unregister($autoloader);
        }

        if (file_exists($dir . '/vendor/autoload.php')) {
            require_once($dir . '/vendor/autoload.php');
        }

        spl_autoload_register(function ($class) use ($dir, $ns) {
            if (strpos($class, $ns) === 0) {
                // main application library
                $file = $dir . '/libs/' . str_replace('\\', '/', substr($class, strlen($ns))) . '.php';

                require_once($file);
            }
        }, true, true);

        // main
        $analyze = function ($page) use (&$analyze) {
            static $processed = array();

            if (in_array($page, $processed)) {
                return;
            }

            $processed[] = $page;

            try {
                $class = new \ReflectionClass($page);
            } catch (\Exception $e) {
                return;
            }

            if (!$class->hasProperty('next_pages')) {
                return;
            }

            $tmp = $class->getProperty('next_pages');
            $tmp->setAccessible(true);

            $obj = new $page();
            $pages = $tmp->getValue($obj);

            asort($pages);

            // process next_pages
            foreach ($pages as $k => $v) {
                printf(
                    "\"%s\" -> \"%s\" [label=%s];\n",
                    addcslashes('\\' . ltrim($page, '\\'), '\\'),
                    addcslashes('\\' . ltrim($v, '\\'), '\\'),
                    ($k == '' ? 'default' : $k)
                );

                $analyze("\\$v");
            }
        };

        print "digraph unix {\nsize=\"10,10\"\nnode [color=lightblue2, style=filled];\n";
        print "rankdir=LR;\n";

        $entry = '\\' . $ns . 'app\\entry';

        $analyze($entry);

        print "}\n";
    }
}
