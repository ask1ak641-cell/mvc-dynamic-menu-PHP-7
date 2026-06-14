<?php
/**
 * Model: User
 * Menangani semua operasi database untuk tabel users
 */
class User extends BaseModel
{
    /** @var string */
    protected $table = 'users';

    /**
     * Cari user berdasarkan username atau email
     * @return array|false
     */
    public function findByLogin($login)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.username = ? OR u.email = ? 
                LIMIT 1";
        return $this->db->fetch($sql, [$login, $login]);
    }

    /**
     * Ambil semua user dengan informasi role
     */
    public function allWithRole(): array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.id DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil permissions user berdasarkan role
     */
    public function getPermissions(int $roleId): array
    {
        $sql = "SELECT m.url, m.name as menu_name
                FROM role_permissions rp
                JOIN menus m ON rp.menu_id = m.id
                WHERE rp.role_id = ? AND m.is_active = 1";
        $results = $this->db->fetchAll($sql, [$roleId]);
        return array_column($results, 'url');
    }

    /**
     * Update password user
     */
    public function updatePassword(int $userId, string $hashedPassword): int
    {
        $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        return $this->db->execute($sql, [$hashedPassword, $userId]);
    }

    /**
     * Simpan token reset password
     */
    public function setResetToken(int $userId, string $token): void
    {
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "UPDATE {$this->table} SET reset_token = ?, reset_expires = ? WHERE id = ?";
        $this->db->execute($sql, [$token, $expiry, $userId]);
    }

    /**
     * Cari user berdasarkan reset token yang valid
     */
    /** @return array|false */
    public function findByResetToken($token)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE reset_token = ? AND reset_expires > NOW() 
                LIMIT 1";
        return $this->db->fetch($sql, [$token]);
    }

    /**
     * Ambil daftar role (untuk dropdown form user)
     */
    public function getRoles(): array
    {
        $sql = "SELECT id, name FROM roles ORDER BY name";
        return $this->db->fetchAll($sql);
    }
}
