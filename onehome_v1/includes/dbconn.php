<?php
// Use MySQLi exceptions only when a query fails.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect('localhost', 'root', '', 'one_home');
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error() . '. Did you import database/one_home.sql in phpMyAdmin?');
}
mysqli_set_charset($conn, 'utf8mb4');
$docroot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$root = str_replace('\\', '/', realpath(__DIR__ . '/..'));
define('BASE_URL', rtrim(substr($root, strlen($docroot)), '/'));
