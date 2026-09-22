<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}
if (isset($_GET['logout'])) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: index.php');
    exit;
}

$stats = [
    ['label' => 'Total Users', 'value' => '1,248'],
    ['label' => 'Orders', 'value' => '356'],
    ['label' => 'Revenue', 'value' => '$12,480'],
    ['label' => 'Pending Tasks', 'value' => '18'],
];
?>