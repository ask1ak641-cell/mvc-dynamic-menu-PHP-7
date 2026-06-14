<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Auth Helper - Cek login, role, permission
 * ═══════════════════════════════════════════════════════════════
 */
class Auth
{
    public static function isLoggedIn(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'id'       => Session::get('user_id'),
            'username' => Session::get('username'),
            'nama'     => Session::get('nama'),
            'email'    => Session::get('email'),
            'role_id'  => Session::get('role_id'),
            'role_name'=> Session::get('role_name'),
        ];
    }

    public static function role(): string
    {
        return Session::get('role_name', 'guest');
    }

    public static function hasRole(string $roles): bool
    {
        $userRole = self::role();
        $allowedRoles = array_map('trim', explode(',', $roles));
        return in_array($userRole, $allowedRoles, true);
    }

    public static function canAccessUrl($path = null)
    {
        if ($path === null) {
            $path = '/' . trim(($_GET['url'] ?? ''), '/');
        }
        if ($path === '//') $path = '/';

        // Admin selalu punya akses
        if (self::role() === 'admin') return true;

        // Path yang bisa diakses semua user yang login
        if ($path === '/' || $path === '/dashboard' || $path === '/login' || $path === '/logout') {
            return true;
        }

        $permissions = Session::get('permissions', []);
        if (!is_array($permissions)) return false;

        foreach ($permissions as $allowed) {
            if (!is_string($allowed)) continue;
            if ($path === $allowed || strpos($path, $allowed . '/') === 0) {
                return true;
            }
        }
        return false;
    }

    public static function can(string $permission): bool
    {
        $permissions = Session::get('permissions', []);
        if (!is_array($permissions)) return false;
        return in_array($permission, $permissions, true);
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set('user_id', (int) ($user['id'] ?? 0));
        Session::set('username', $user['username'] ?? '');
        Session::set('nama', $user['nama'] ?? $user['username'] ?? '');
        Session::set('email', $user['email'] ?? '');
        Session::set('role_id', (int) ($user['role_id'] ?? 0));
        Session::set('role_name', $user['role_name'] ?? 'guest');
        Session::set('permissions', $user['permissions'] ?? []);
        Session::set('_last_activity', time());
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
