<?php
/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  PHP Native MVC Boilerplate - Entry Point                   ║
 * ║  Semua request masuk melalui file ini                       ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

// Mulai session dengan pengaturan aman
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Konstanta Aplikasi ───────────────────────────────────────
define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', ROOT_DIR . '/app');
define('SYSTEM_DIR', ROOT_DIR . '/system');
define('VIEW_DIR', APP_DIR . '/views');
define('PUBLIC_DIR', __DIR__);

// ─── Error Reporting ──────────────────────────────────────────
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ─── Autoloader Sederhana ─────────────────────────────────────
spl_autoload_register(function ($className) {
    // Validasi nama class - hanya alphanumeric dan underscore
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $className)) {
        return;
    }

    $paths = [
        SYSTEM_DIR . '/' . $className . '.php',
        APP_DIR . '/controllers/' . $className . '.php',
        APP_DIR . '/models/' . $className . '.php',
        APP_DIR . '/helpers/' . $className . '.php',
        APP_DIR . '/middleware/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ─── Load Config ──────────────────────────────────────────────
require_once APP_DIR . '/config/config.php';
require_once APP_DIR . '/config/database.php';

// ─── Load Helpers (selalu dibutuhkan) ─────────────────────────
require_once APP_DIR . '/helpers/URL.php';
require_once APP_DIR . '/helpers/Session.php';
require_once APP_DIR . '/helpers/Security.php';

// ─── Security Headers ─────────────────────────────────────────
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// ─── Bootstrap Router ─────────────────────────────────────────
$router = new Router();

// ─── Daftarkan Routes ─────────────────────────────────────────
// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');
$router->get('/', 'DashboardController@index');

// User Management (hanya admin)
$router->get('/user', 'UserController@index');
$router->get('/user/create', 'UserController@create');
$router->post('/user/store', 'UserController@store');
$router->get('/user/edit/{id}', 'UserController@edit');
$router->post('/user/edit/{id}', 'UserController@update');
$router->get('/user/delete/{id}', 'UserController@delete');
$router->post('/user/toggle-active/{id}', 'UserController@toggleActive');

// Role Management
$router->get('/role', 'RoleController@index');
$router->get('/role/create', 'RoleController@create');
$router->post('/role/store', 'RoleController@store');
$router->get('/role/edit/{id}', 'RoleController@edit');
$router->post('/role/edit/{id}', 'RoleController@update');
$router->get('/role/delete/{id}', 'RoleController@delete');

// Dynamic Menu Management
$router->get('/menu', 'MenuController@index');
$router->get('/menu/create', 'MenuController@create');
$router->post('/menu/store', 'MenuController@store');
$router->get('/menu/edit/{id}', 'MenuController@edit');
$router->post('/menu/edit/{id}', 'MenuController@update');
$router->get('/menu/delete/{id}', 'MenuController@delete');



// ─── Jalankan Router ──────────────────────────────────────────
$router->dispatch();
