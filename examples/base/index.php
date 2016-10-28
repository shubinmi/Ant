<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

use Ant\Application\Application;

define('APPLICATION_ENV', 'local');

// Define paths of folders with configuration files
$configDirs = [
    __DIR__ . '/Config/Production'
];
if (defined('APPLICATION_ENV') && APPLICATION_ENV == 'local') {
    $configDirs[] = __DIR__ . '/Config/Local';
}

$app = new Application();
$app->loadConfig($configDirs);
$app->run();