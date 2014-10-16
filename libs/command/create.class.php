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
    use \org\octris\core\config as config;
    use \org\octris\core\provider as provider;
    use \org\octris\cliff\stdio as stdio;
    use \org\octris\core\validate as validate;
    use \org\octris\cliff\args as args;

    /**
     * Create a new project.
     *
     * @octdoc      c:command/create
     * @copyright   copyright (c) 2014 by Harald Lapp
     * @author      Harald Lapp <harald@octris.org>
     */
    class create extends \org\octris\cliff\app\command
    /**/
    {
        /**
         * Constructor.
         *
         * @octdoc  m:create/__construct
         * @param   string                              $name               Name of command.
         */
        public function __construct($name)
        /**/
        {
            parent::__construct($name);
        }
        
        /**
         * Configure command arguments.
         *
         * @octdoc  m:create/configure
         */
        public function configure()
        /**/
        {
            $this->addOption(['p', 'project'], args::T_VALUE | args::T_REQUIRED)->addValidator(function($value) {
                $validator = new \org\octris\core\validate\type\project();
                return $validator->validate($validator->preFilter($value));
            }, 'invalid project name specified');
            $this->addOption(['t', 'type'], args::T_VALUE | args::T_REQUIRED)->addValidator(function($value) {
                return in_array($value, ['web', 'cli', 'lib']);
            }, 'invalid project type specified');
            $this->addOption(['d', 'define'], args::T_KEYVALUE)->addValidator(function($value, $key) {
                return (in_array($key, ['info.company', 'info.author', 'info.email']) && $value != '');
            }, 'invalid argument value');
            
            $this->addOperand(1, 1)->addValidator(function($value) {
                return is_dir($value);
            }, 'specified path is not a directory or directory not found');
        }
        
        /**
         * Return command description.
         *
         * @octdoc  m:create/getDescription
         */
        public static function getDescription()
        /**/
        {
            return 'Create a new project.';
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
    -p      A valid name for the project in the form of a reversed domain
            name. 
            
    -t      A valid type for the project
    
    -d      Additional definitions to set that will be used to rewrite 
            comments in the project skeleton. The current supported fields
            that can be set are: 'info.company', 'info.author' and
            'info.email'.

EXAMPLES
    Create a test project:
    
        $ ./octris create -p org.octris.test -t web \
                -d info.company="Foo Inc." \
                -d info.author="Bar Baz" \
                -d info.email="baz@example.org" \
                ~/tmp/
EOT;
        }

        /**
         * Helper method to test whether a file is binary or text file.
         *
         * @octdoc  m:create/isBinary
         * @param   string          $file               File to test.
         * @param   string          $size               Optional block size to test.
         * @return  bool                                Returns true for binaries.
         */
        protected function isBinary($file, $size = 2048)
        /**/
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
         * @octdoc  m:create/run
         * @param   \org\octris\cliff\args\collection        $args           Parsed arguments for command.
         */
        public function run(\org\octris\cliff\args\collection $args)
        /**/
        {
            $project = $args['project'];
            $type    = $args['type'];

            $tmp    = explode('.', $project);
            $module = array_pop($tmp);
            $domain = implode('.', array_reverse($tmp));

            if (!isset($args[0])) {
                throw new \org\octris\cliff\exception\argument(sprintf("no destination path specified"));
            } elseif (!is_dir($args[0])) {
                throw new \org\octris\cliff\exception\argument('specified path is not a directory or directory not found');
            } else {
                $dir = $args[0] . '/' . $project;

                if (is_dir($dir)) {
                    throw new \org\octris\cliff\exception\argument(sprintf("project directory already exists '%s'", $dir));
                }
            }
            
            $year = date('Y');

            // handle project configuration
            $prj = new config('org.octris.core', 'project.create');

            $prj->setDefaults(array(
                'info.company' => (isset($data['company']) ? $data['company'] : ''),
                'info.author'  => (isset($data['author']) ? $data['author'] : ''),
                'info.email'   => (isset($data['email']) ? $data['email'] : '')
            ));

            if ($domain != '') {
                $prj['info.domain'] = $domain;
            }

            // collect information and create configuration for new project
            $filter = $prj->filter('info');

            foreach ($filter as $k => $v) {
                $prj['info.' . $k] = stdio::getPrompt(sprintf("%s [%%s]: ", $k), $v);
            }

            $prj->save();

            print "\n";

            // build data array
            $ns = implode(
                '\\',
                array_reverse(
                    explode('.', $prj['info.domain'])
                )
            ) . '\\' . $module;

            $project = str_replace('\\', '.', $ns);

            $data = array_merge($prj->filter('info')->getArrayCopy(true), array(
                'year'      => $year,
                'module'    => $module,
                'namespace' => $ns,
                'directory' => $project,
                'project'   => $project
            ));

            // create project
            $src = __DIR__ . '/../../data/skel/' . $type . '/';
            if (!is_dir($src)) {
                throw new \org\octris\cliff\exception\application(sprintf("unable to locate template directory '%s'\n", $src));

                return 1;
            }

            // process skeleton and write project files
            $tpl = new \org\octris\core\tpl();
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
                    $cmp = $tpl->fetch($rel, \org\octris\core\tpl::T_ESC_NONE);

                    file_put_contents($dst, $cmp);
                } else {
                    copy($filename, $dst);
                }

                chmod($dst, 0644);
            }
        }
    }
}
