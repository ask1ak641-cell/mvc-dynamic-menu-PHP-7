<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  URL Helper - Memudahkan generate URL
 * ═══════════════════════════════════════════════════════════════
 */
class URL
{
    /**
     * Generate absolute URL
     * Contoh: URL::base('/user') → http://localhost/app/user
     */
    public static function base(string $path = ''): string
    {
        return APP_URL . $path;
    }

    /**
     * Generate URL ke asset (CSS, JS, images)
     * Contoh: URL::asset('css/style.css') → http://localhost/app/public/assets/css/style.css
     */
    public static function asset(string $path): string
    {
        return APP_URL . '/assets/' . ltrim($path, '/');
    }

    /**
     * Redirect ke URL
     */
    public static function redirect(string $path): void
    {
        header('Location: ' . self::base($path));
        exit;
    }

    /**
     * Ambil current URL
     */
    public static function current(): string
    {
        return self::base($_GET['url'] ?? '');
    }

    /**
     * Cek apakah URL saat ini cocok dengan path tertentu
     * Berguna untuk menandai menu aktif
     */
    public static function isActive(string $path): bool
    {
        $current = '/' . trim(($_GET['url'] ?? ''), '/');
        return strpos($current, $path) === 0;
    }
}
