<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        if (!defined('SITE_ROOT')) require_once dirname(__DIR__) . '/includes/config.php';
        header("Location: " . siteUrl('login.php'));
        exit;
    }
}

function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        if (!defined('SITE_ROOT')) require_once dirname(__DIR__) . '/includes/config.php';
        header("Location: " . siteUrl('admin/index.php'));
        exit;
    }
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}
?>
