<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Security Helper - XSS Filtering, CSRF, Input Sanitization
 * ═══════════════════════════════════════════════════════════════
 */
class Security
{
    /**
     * Sanitize output untuk HTML (XSS prevention)
     * Gunakan ini saat menampilkan data ke HTML, BUKAN saat menyimpan ke database
     */
    public static function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars((string) $input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Alias singkat untuk sanitize / escape output
     */
    public static function e($input)
    {
        return self::sanitize($input);
    }

    /**
     * Generate hidden input field untuk CSRF token
     */
    public static function csrfField(): string
    {
        $token = Session::csrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Sanitize nama file - mencegah directory traversal
     */
    public static function sanitizeFilename(string $filename): string
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        $filename = preg_replace('/\.{2,}/', '.', $filename);
        return $filename;
    }

    /**
     * Generate random string yang aman
     */
    public static function randomString(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Escape karakter special untuk LIKE query
     * Mencegah LIKE injection (% dan _)
     */
    public static function escapeLike(string $str): string
    {
        return addcslashes($str, '%_');
    }
}
