<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Auth Middleware - Proteksi halaman berdasarkan login & role
 * ═══════════════════════════════════════════════════════════════
 *
 * Cara pakai: panggil AuthMiddleware::handle() di constructor controller
 *   AuthMiddleware::handle()             → harus login
 *   AuthMiddleware::handle('admin')      → harus admin
 *   AuthMiddleware::handle('admin,operator') → admin atau operator
 */
class AuthMiddleware
{
    public static function handle(?string $requiredRoles = null): void
    {
        // Cek login
        if (!Auth::isLoggedIn()) {
            Session::setFlash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . URL::base('/login'));
            exit;
        }

        // Cek idle timeout
        Session::checkIdleTimeout();

        // Cek role (jika ditentukan)
        if ($requiredRoles !== null) {
            if (!Auth::hasRole($requiredRoles)) {
                Session::setFlash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                header('Location: ' . URL::base('/dashboard'));
                exit;
            }
        } else {
            // Tanpa role spesifik → cek permission berdasarkan URL
            if (!Auth::canAccessUrl()) {
                Session::setFlash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                header('Location: ' . URL::base('/dashboard'));
                exit;
            }
        }
    }
}
