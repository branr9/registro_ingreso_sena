<?php

// Router para pruebas locales con `php -S 127.0.0.1:8765 tests/router.php`.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$publicFile = dirname(__DIR__) . '/public' . $path;

if ($path !== '/' && is_file($publicFile)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require dirname(__DIR__) . '/public/index.php';
