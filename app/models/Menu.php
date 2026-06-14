<?php
/**
 * Model: Menu
 * Menangani dynamic menu management
 */
class Menu extends BaseModel
{
    /** @var string */
    protected $table = 'menus';

    /**
     * Ambil semua menu (parent + children) untuk sidebar
     * 
     * @param int|null $roleId Filter menu berdasarkan role (null = semua)
     * @return array Menu tree yang sudah terstruktur
     */
    /** @param int|null $roleId */
    public function getMenuTree($roleId = null)
    {
        if ($roleId !== null) {
            // Ambil menu yang diizinkan untuk role ini
            $sql = "SELECT m.* FROM {$this->table} m
                    JOIN role_permissions rp ON m.id = rp.menu_id
                    WHERE rp.role_id = ? AND m.is_active = 1
                    ORDER BY m.parent_id, m.sort_order, m.name";
            $menus = $this->db->fetchAll($sql, [$roleId]);
        } else {
            // Admin: semua menu
            $sql = "SELECT * FROM {$this->table} 
                    WHERE is_active = 1 
                    ORDER BY parent_id, sort_order, name";
            $menus = $this->db->fetchAll($sql);
        }

        return $this->buildTree($menus);
    }

    /**
     * Build tree structure dari flat array
     */
    private function buildTree(array $menus, int $parentId = 0): array
    {
        $tree = [];
        foreach ($menus as $menu) {
            if ((int) $menu['parent_id'] === $parentId) {
                $children = $this->buildTree($menus, (int) $menu['id']);
                if ($children) {
                    $menu['children'] = $children;
                }
                $tree[] = $menu;
            }
        }
        return $tree;
    }

    /**
     * Ambil menu parent (untuk dropdown di form menu)
     */
    public function getParentMenus(): array
    {
        $sql = "SELECT id, name FROM {$this->table} 
                WHERE parent_id = 0 OR parent_id IS NULL 
                ORDER BY sort_order, name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil semua menu flat (untuk management)
     */
    public function allFlat(): array
    {
        $sql = "SELECT m.*, p.name as parent_name 
                FROM {$this->table} m
                LEFT JOIN {$this->table} p ON m.parent_id = p.id
                ORDER BY m.parent_id, m.sort_order, m.name";
        return $this->db->fetchAll($sql);
    }
}
