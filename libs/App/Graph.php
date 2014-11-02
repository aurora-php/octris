<?php

/*
 * This file is part of the octris/core.
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
 * Create a page graph of a project.
 *
 * @copyright   copyright (c) 2011-2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Graph extends \Octris\Cliff\Args\Command
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
     * Return command description.
     *
     */
    public static function getDescription()
    {
        return 'Create a page graph of a project.';
    }

    /**
     * Return command manual.
     *
     */
    public static function getManual()
    {
            return <<<EOT
NAME
    octris graph - create a page graph of a project.

SYNOPSIS
    octris graph     <project-path>

DESCRIPTION
    This command is used to analyze the page flow of a project and
    to create a graph from it that can be visualized using the dot
    utility of graphviz.

    The generated graph will be printed to stdout and can as such
    be directly processed by the dot utility.

OPTIONS

EXAMPLES
    Create a graph of a project in PDF format:

        $ ./octris graph ~/tmp/octris/test |\
                dot -Tpdf

HINTS
    Graphviz is available for various platforms and can be downloaded
    from: http://www.graphviz.org/
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
            throw new \Octris\Cliff\Exception\Argument(sprintf("no project path specified"));
        } elseif (!is_dir($args[0])) {
            throw new \Octris\Cliff\Exception\Argument('specified path is not a directory or directory not found');
        } else {
            $dir = rtrim($args[0], '/');
        }

        if (!is_dir($dir . '/libs/app') || !is_file($dir . '/libs/app/entry.php')) {
            throw new \Octris\Cliff\Exception\Argument(sprintf(
                '\'%s\' does not seem to be a web application created with the OCTRiS framework',
                $dir
            ));
        }

        $project = basename($dir);
        $ns      = str_replace('.', '\\', $project) . '\\';

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
