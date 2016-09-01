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
 * Start HTTPD backend.
 *
 * @copyright   copyright (c) 2014-2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Httpd implements \Octris\Cli\App\ICommand
{
    /**
     * Bind to IP address.
     *
     * @type    string
     */
    protected static $bind_ip = '127.0.0.1';

    /**
     * Bind to port.
     *
     * @type    int
     */
    protected static $bind_port = '3000';

    /**
     * Bind to SSL port.
     *
     * @type    int
     */
    protected static $bind_ssl_port = '3443';

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
        $command->setHelp('Httpd server for testing purpose.');
        $command->setDescription('This command uses PHP\'s builtin webserver for testing a project.');
        $command->setExample(<<<EXAMPLE
Start webserver for a project:

    $ ./octris httpd ~/tmp/test
EXAMPLE
        );
        $command->addOption('ip-address', '-b | --bind-ip <ip-address>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'A single IP that the webserver will be listening on (defaults to ' . self::$bind_ip . ').'
        ]);
        $command->addOption('port', '-p | --port <port>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'A port number the webserver will be listening on (defaults to ' . self::$bind_port . ').'
        ])->addValidator(function($value) {
            return ctype_digit($value);
        });
        $command->addOption('detach', '-d | --detach', true, [
            'help' => 'Run in background.'
        ]);
        $command->addOption('ssl-port', '--with-ssl <ssl-port>', ['\Aaparser\Coercion', 'value'], [
            'help' => 'A port number the webserver will be listening on for SSL connections (defaults to ' . self::$bind_ssl_port . ').'
        ])->addValidator(function($value) {
            return ctype_digit($value);
        })->addValidator(function($value) {
            var_dump(extension_loaded('openssl'));
        });
        $op = $command->addOperand('project-path', 1, [
            'help' => 'Project path.'
        ]);
        \Octris\Util\Validator::addProjectPathCheck($op);
    }

    /**
     * Run command.
     *
     * @param   array           $options                    Cli options.
     * @param   array           $operands                   Cli operands.
     */
    public function run(array $options, array $operands)
    {
        $port = (isset($options['port']) ? $options['port'] : self::$bind_port);
        $ip = (isset($options['ip-address']) ? $options['ip-address'] : self::$bind_ip);

        $docroot = $operands['project-path'][0] . '/host/';

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $router = array_pop($trace)['file'];

        // start php's builtin webserver
        $pid = exec(sprintf(
            '((%s -d output_buffering=on -t %s -S %s:%s %s 1>/dev/null 2>&1 & echo $!) &)',
            PHP_BINARY,
            $docroot,
            $ip,
            $port,
            $router
        ));
        sleep(1);

        if (ctype_digit($pid) && posix_kill($pid, 0)) {
            printf("listening on '%s:%s', PID is %d\n", $ip, $port, $pid);
            die(0);
        } else {
            printf("Unable to start webserver on '%s:%s'\n", $ip, $port);
            die(255);
        }
    }
}
