<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Konfigurasi Aplikasi
 * ═══════════════════════════════════════════════════════════════
 *
 * Ganti nilai di bawah sesuai environment Anda
 */
define('APP_NAME', 'PHP MVC Boilerplate');
define('APP_URL', 'http://localhost/template');  // Ganti dengan URL aplikasi Anda
define('APP_VERSION', '1.0.0');

// Set ke false saat deploy ke production!
define('APP_DEBUG', true);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Session auto-logout (detik) - 30 menit session lifetime
define('SESSION_LIFETIME', 1800);
// 15 menit idle → auto logout
define('SESSION_IDLE_TIMEOUT', 900);

// Email (SMTP Gmail) - opsional
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');   // GANTI!
define('MAIL_PASSWORD', 'your-app-password');       // GANTI! (App Password Gmail)
define('MAIL_FROM_ADDRESS', 'noreply@yourapp.com');
define('MAIL_FROM_NAME', APP_NAME);
