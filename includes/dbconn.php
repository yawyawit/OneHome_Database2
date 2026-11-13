<?php
$conn = mysqli_connect('localhost', 'root', '', 'one_home', 3307);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error() . '. Did you import database/one_home.sql in phpMyAdmin?');
}
mysqli_set_charset($conn, 'utf8mb4');
mysqli_report(MYSQLI_REPORT_OFF);

$docroot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$root = str_replace('\\', '/', realpath(__DIR__ . '/..'));
define('BASE_URL', rtrim(substr($root, strlen($docroot)), '/'));
