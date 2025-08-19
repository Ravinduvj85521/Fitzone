<?php
/**
 * FitZone Configuration File
 * Handles database connection and core configuration
 */

// Verification constant (must be first)
define('FITZONE_CONFIG_LOADED', true);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Management
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    ini_set('session.use_strict_mode', 1);
    session_start();

    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fitzone');

// Establish database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log('Database Error: ' . $e->getMessage());
    die("<h2>Service Temporarily Unavailable</h2>
        <p>We're experiencing technical difficulties. Please try again later.</p>");
}

/**
 * Sanitize input data
 */
function sanitize($data) {
    global $conn;
    if (!is_string($data)) return $data;
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $conn->real_escape_string($data);
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}