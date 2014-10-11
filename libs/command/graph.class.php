<?php

/*
 * This file is part of the 'octris' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace octris\command {
    use \org\octris\core\provider as provider;
    use \org\octris\core\validate as validate;

    /**
     * Create a page graph of a project.
     *
     * @octdoc      c:command/graph
     * @copyright   copyright (c) 2011-2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class graph extends \octris\command
    /**/
    {
        /**
         * Return command description.
         *
         * @octdoc  m:graph/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Create a page graph of a project.';
        }

        /**
         * Return command manual.
         *
         * @octdoc  m:create/getManual
         */
        public static function getManual()
        /**/
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
    
        $ ./octris graph ~/tmp/org.octris.test |\
                dot -Tpdf

HINTS
    Graphviz is available for various platforms and can be downloaded
    from: http://www.graphviz.org/
EOT;
        }

        /**
         * Run command.
         *
         * @octdoc  m:graph/run
         */
        public function run()
        /**/
        {
            // import required parameters
            $args = provider::access('args');

            if ($args->isExist(0) && $args->isValid(0, validate::T_PATH)) {
                $dir = rtrim($args->getValue(0), '/');
            } else {
                $this->setError('specified path is not a directory or directory not found');
                
                return 1;
            }

            if (!is_dir($dir . '/libs/app') || !is_file($dir . '/libs/app/entry.class.php')) {
                $this->setError(sprintf('\'%s\' does not seem to be a web application created with the OCTRiS framework', $dir));
                
                return 1;
            }
            
            $project = basename($dir);
            $ns      = str_replace('.', '\\', $project) . '\\';
            
            /*
             * install new project-specific autoloader
             */
            spl_autoload_unregister(array('\octris\main', 'autoload'));
            spl_autoload_register(function($classpath) use ($dir, $ns) {
                if (strpos($classpath, $ns) === 0) {
                    // main application library
                    $file = $dir . '/libs/' . str_replace('\\', '/', substr($classpath, strlen($ns))) . '.class.php';
                    
                    include_once($file);
                } else {
                    // vendor library
                    $classpath = preg_replace('|\\\\|', '.', ltrim($classpath, '\\\\'), 2);
                    $classpath = preg_replace('|\\\\|', '/libs/', $classpath, 1);
                    $classpath = preg_replace('|\\\\|', '/', $classpath);
                    
                    $file = $dir . '/vendor/' . $classpath . '.class.php';
                    
                    include_once($file);
                }
            });
            
            // main
            $analyze = function($page) use (&$analyze) {
                static $processed = array();

                if (in_array($page, $processed)) {
                    return;
                }

                $processed[] = $page;

                try {
                    $class = new \ReflectionClass($page);
                } catch(\Exception $e) {
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
}