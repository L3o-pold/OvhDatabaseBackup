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

$ovhBackup = new OvhDatabaseBackup\Backup();
$status = $ovhBackup->backupDatabase();

if ($status === true) {
    http_response_code(200);
} else {
    http_response_code(500);
}