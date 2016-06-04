<?php

/**
 * @package OvhDatabaseBackup
 * @author  Léopold Jacquot {@link https://www.leopoldjacquot.com}
 */
require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require __DIR__.DIRECTORY_SEPARATOR.'config/config.php';

if (defined('DEBUG') && DEBUG === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

try {
    $ovhBackup = new OvhDatabaseBackup\Backup();
    $ovhBackup->backupDatabase();
    http_response_code(200);
} catch(Exception $e) {
    http_response_code(500);
}