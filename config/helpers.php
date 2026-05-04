<?php
/**
 * App config + helpers
 * IMPORTANT: change BASE_URL to match your folder name in htdocs
 */

define('BASE_URL', '/sport_plus');
define('APP_NAME', 'Sport+');

// Errors (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === URL helpers ===
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset($path) {
    return BASE_URL . '/public/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

// === Auth helpers ===
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) redirect('login');
}

function requireAdmin() {
    if (!isLoggedIn()) redirect('login');
    if (!isAdmin()) redirect('dashboard');
}

// === Output escape (anti-XSS) ===
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// === Flash messages ===
function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

// === UI Preferences ===
function getLang():     string { return $_SESSION['pref_lang']     ?? 'EN'; }
function getCurrency(): string { return $_SESSION['pref_currency'] ?? 'MAD'; }
function getTheme():    string { return $_SESSION['pref_theme']    ?? 'dark'; }

function t(string $en, string $fr = ''): string {
    return (getLang() === 'FR' && $fr !== '') ? $fr : $en;
}

function fmtPrice($mad): string {
    if (getCurrency() === 'EUR') {
        return number_format((float)$mad / 10, 0) . ' €';
    }
    return $mad . ' MAD';
}
