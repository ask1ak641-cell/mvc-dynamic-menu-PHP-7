<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Router Class - Menangani routing dan dispatch ke controller
 * ═══════════════════════════════════════════════════════════════
 */
class Router
{
    private $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url    = $_GET['url'] ?? '/';
        $url    = '/' . trim($url, '/');
        if ($url === '//') $url = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (preg_match($route['pattern'], $url, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
        echo '<p>URL: ' . htmlspecialchars($url) . '</p>';
        echo '<p><a href="' . htmlspecialchars(APP_URL) . '">Kembali ke Dashboard</a></p>';
    }

    private function callHandler(string $handler, array $params): void
    {
        $parts = explode('@', $handler);
        if (count($parts) !== 2) {
            http_response_code(500);
            die("Handler routing tidak valid: " . htmlspecialchars($handler));
        }

        [$controllerName, $methodName] = $parts;

        // Validasi nama controller dan method - hanya alphanumeric
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $controllerName) ||
            !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $methodName)) {
            http_response_code(400);
            die("Controller atau method tidak valid.");
        }

        if (!class_exists($controllerName)) {
            http_response_code(404);
            die("Controller <b>" . htmlspecialchars($controllerName) . "</b> tidak ditemukan.");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            http_response_code(404);
            die("Method <b>" . htmlspecialchars($methodName) . "</b> tidak ditemukan di controller <b>" . htmlspecialchars($controllerName) . "</b>.");
        }

        call_user_func_array([$controller, $methodName], $params);
    }
}
