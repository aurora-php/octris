<?php

if (in_array($argv[2], ['compile', 'lint'])) {
    $cmd = $argv[2];

    array_unshift($argv, implode(' ', array_splice($argv, 0, 3)));

    $argc = count($argv);

    require_once(__DIR__ . '/../../bin/' . $cmd . '.php');
} else {
    printf(
        "%s: unknown tool '%s'!\n",
        basename($argv[0]),
        $argv[2]
    );
    exit(1);
}
