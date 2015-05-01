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

use \Octris\Core\Provider as provider;
use \Octris\Core\Validate as validate;
use \Octris\Cliff\Args    as args;

/**
 * Start HTTPD backend.
 *
 * @copyright   copyright (c) 2014 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Httpd extends \Octris\Cliff\Args\Command implements \Octris\Cliff\Args\IManual
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
    protected static $bind_port = '8888';

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
     * Configure command arguments.
     */
    public function configure()
    {
        $this->addOption(['b', 'bind-ip'], args::T_VALUE | args::T_REQUIRED, 'arg', self::$bind_ip)->addValidator(function ($value) {
            return true;
        }, 'invalid IP address specified')->setHelp('A single IP that the webserver will be listening on (defaults to ' . self::$bind_ip . ').');
        $this->addOption(['p', 'port'], args::T_VALUE | args::T_REQUIRED, 'arg', self::$bind_port)->addValidator(function ($value) {
            return ctype_digit($value);
        }, 'invalid port number specified')->setHelp('A port number the webserver will be listening on (defaults to ' . self::$bind_port . ').');

        $this->addOperand(1, 'project-path')->addValidator(function ($value) {
            return (is_dir($value) && is_dir($value . '/host'));
        }, 'specified path is not a directory or directory not found or directory contains no "host" directory and therefore is probably not an octris web project')->setHelp('Path to a project.');
    }

    /**
     * Return command description.
     */
    public static function getDescription()
    {
        return 'Httpd backend.';
    }

    /**
     * Return command manual.
     */
    public static function getManual()
    {
            return sprintf(<<<EOT
NAME
    octris httpd - start http backend for testing project.

SYNOPSIS
    octris httpd    [-b <ip-address>]
                    [-p <port-number>]
                    <project-path>

DESCRIPTION
    This command uses PHP's builtin webserver for testing a project.

OPTIONS
    -b      A single IP that the webserver will be listening on
            (defaults to %s).

    -p      A port number the webserver will be listening on
            (defaults to %s).

EXAMPLES
    Example:

        $ ./octris httpd ~/tmp/test
EOT
            , self::$bind_ip, self::$bind_port
        );
    }

    /**
     * Run command.
     *
     * @param   \Octris\Cliff\Args\Collection        $args           Parsed arguments for command.
     */
    public function run(\Octris\Cliff\Args\Collection $args)
    {
        $ip = $args['bind-ip'];
        $port = $args['port'];

        $docroot = $args[0] . '/host/';

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
