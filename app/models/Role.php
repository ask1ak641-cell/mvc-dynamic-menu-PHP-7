<?php
/**
 * Model: Role
 * Menangani role management dan permissions
 */
class Role extends BaseModel
{
    /** @var string */
    protected $table = 'roles';

    /**
     * Ambil permissions (menu_id) untuk role tertentu
     */
    public function getPermissions(int $roleId): array
    {
        $sql = "SELECT menu_id FROM role_permissions WHERE role_id = ?";
        $results = $this->db->fetchAll($sql, [$roleId]);
        return array_column($results, 'menu_id');
    }

    /**
     * Simpan permissions untuk role (replace all)
     */
    public function savePermissions(int $roleId, array $menuIds): void
    {
        // Hapus semua permissions lama
        $this->db->execute("DELETE FROM role_permissions WHERE role_id = ?", [$roleId]);

        // Insert permissions baru
        if (!empty($menuIds)) {
            $placeholders = [];
            $params = [];
            foreach ($menuIds as $menuId) {
                $placeholders[] = "(?, ?)";
                $params[] = $roleId;
                $params[] = (int) $menuId;
            }

            $sql = "INSERT INTO role_permissions (role_id, menu_id) VALUES " . implode(', ', $placeholders);
            $this->db->execute($sql, $params);
        }
    }

    /**
     * Ambil semua menu (untuk form permissions)
     */
    public function getAllMenus(): array
    {
        $sql = "SELECT * FROM menus WHERE is_active = 1 ORDER BY parent_id, sort_order, name";
        return $this->db->fetchAll($sql);
    }
}
