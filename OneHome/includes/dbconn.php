<?php
mysqli_report(MYSQLI_REPORT_OFF);

$db_host = getenv('ONEHOME_DB_HOST') ?: 'localhost';
$db_user = getenv('ONEHOME_DB_USER') ?: 'root';
$db_pass = getenv('ONEHOME_DB_PASS');
if ($db_pass === false) $db_pass = '';
$db_name = getenv('ONEHOME_DB_NAME') ?: 'one_home';
$db_port = (int) (getenv('ONEHOME_DB_PORT') ?: 3307);

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
if (!$conn) {
    error_log('One Home database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Unable to connect to the database. Check the One Home database settings.');
}
mysqli_set_charset($conn, 'utf8mb4');

$document_root = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$project_root = realpath(__DIR__ . '/..');
$document_root = str_replace('\\\\', '/', (string) $document_root);
$project_root = str_replace('\\\\', '/', (string) $project_root);
define('BASE_URL', rtrim(substr($project_root, strlen($document_root)), '/'));
