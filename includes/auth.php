<?php
/**
 * FitZone Authentication Functions
 * Requires config.php to be included first
 */

function isLoggedIn() {
    return isset($_SESSION['member_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirectIfNotLoggedIn($redirectTo = '/login.php') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: $redirectTo");
        exit();
    }
}

function redirectIfNotAdmin($redirectTo = '/login.php') {
    if (!isAdmin()) {
        header("Location: $redirectTo");
        exit();
    }
}