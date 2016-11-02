<?php
use Ant\Application\Application;

require_once '../init.php';

$configDirs = [BASE_PATH . '/config/production'];
if (APPLICATION_ENV == 'local') {
    $configDirs[] = BASE_PATH . '/config/local';
}

$app = new Application();
$app->loadConfig($configDirs);
$app->run();