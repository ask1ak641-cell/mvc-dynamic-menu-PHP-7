<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Session Helper - Manajemen session aman
 * ═══════════════════════════════════════════════════════════════
 */
class Session
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function setFlash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        if (isset($_SESSION['_flash'][$key])) {
            $message = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Generate atau ambil CSRF token
     * Token bersifat persisten per session (tidak dihapus setelah validasi)
     * karena digunakan oleh multiple forms di halaman yang sama
     */
    public static function csrfToken(): string
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    /**
     * Validasi CSRF token menggunakan hash_equals (timing-safe)
     * Token akan di-regenerate setelah validasi untuk mencegah replay attack
     */
    public static function validateCsrf(string $token): bool
    {
        $stored = self::get('csrf_token', '');
        if (empty($stored) || empty($token)) {
            return false;
        }
        $valid = hash_equals($stored, $token);
        // Regenerate token setelah validasi (one-time use)
        self::set('csrf_token', bin2hex(random_bytes(32)));
        return $valid;
    }

    public static function checkIdleTimeout(): void
    {
        $lastActivity = self::get('_last_activity', 0);
        $now = time();

        if ($lastActivity && ($now - $lastActivity) > SESSION_IDLE_TIMEOUT) {
            self::destroy();
            header('Location: ' . URL::base('/login?expired=1'));
            exit;
        }

        self::set('_last_activity', $now);
    }
}
