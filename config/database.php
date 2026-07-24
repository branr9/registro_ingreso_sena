<?php
/**
 * Configuración de Base de Datos
 */

$initCommand = class_exists('Pdo\Mysql')
    ? \Pdo\Mysql::ATTR_INIT_COMMAND
    : PDO::MYSQL_ATTR_INIT_COMMAND;

return [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_NAME'] ?? 'sena_acceso',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        $initCommand => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]
];
