<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  BaseController - Kelas dasar untuk semua controller
 * ═══════════════════════════════════════════════════════════════
 */
class BaseController
{
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        ob_start();
        $viewFile = VIEW_DIR . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            http_response_code(404);
            die("View <b>" . htmlspecialchars($view) . "</b> tidak ditemukan.");
        }
        $content = ob_get_clean();

        $layoutFile = VIEW_DIR . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . URL::base($url));
        exit;
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Ambil input POST dengan sanitasi dasar
     * Hanya trim whitespace, tanpa htmlspecialchars (escaping dilakukan saat output)
     */
    protected function input($key, $default = null)
    {
        $value = $_POST[$key] ?? $default;
        if (is_string($value)) {
            return trim($value);
        }
        if (is_array($value)) {
            return array_map('trim', $value);
        }
        return $value;
    }

    /**
     * Ambil input GET dengan sanitasi dasar
     */
    protected function query($key, $default = null)
    {
        $value = $_GET[$key] ?? $default;
        if (is_string($value)) {
            return trim($value);
        }
        return $value;
    }

    /**
     * Validasi CSRF token
     * Hapus token setelah validasi (one-time use) untuk mencegah replay attack
     */
    protected function validateCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !Session::validateCsrf($token)) {
            // Regenerate token baru agar form berikutnya valid
            Session::csrfToken();
            Session::setFlash('error', 'Token CSRF tidak valid atau sudah kadaluarsa. Silakan coba lagi.');
            $this->redirect('/' . trim($_GET['url'] ?? '', '/'));
        }
    }
}
