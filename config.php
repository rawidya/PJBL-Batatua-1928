<?php
// Database configuration and connection helper
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'batatua1928';

function get_db_connection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die('Database connection failed');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function start_session_if_needed(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function require_auth(): void {
    start_session_if_needed();
    if (empty($_SESSION['user_id'])) {
        header('Location: Login-for-admin.php');
        exit;
    }
}

?>


