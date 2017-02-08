<?php

define('BASE_PATH', __DIR__);

if(!defined('APPLICATION_ENV')) {
    if (FALSE === stripos($_SERVER['SERVER_NAME'], 'my-prod-site.domain')) {
        define('APPLICATION_ENV', 'local');
    } else {
        define('APPLICATION_ENV', 'production');
    }
}

require BASE_PATH . '/vendor/autoload.php';
