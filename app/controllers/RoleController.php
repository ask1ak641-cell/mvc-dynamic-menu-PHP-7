<?php
/**
 * Controller: Role Management
 * Admin role (id=1) tidak bisa diedit/dihapus
 */
class RoleController extends BaseController
{
    /** @var Role */
    private $roleModel;

    public function __construct()
    {
        AuthMiddleware::handle('admin');
        $this->roleModel = new Role();
    }

    public function index(): void
    {
        $roles = $this->roleModel->all('name');
        $this->view('role/index', [
            'title' => 'Manajemen Role',
            'roles' => $roles,
        ]);
    }

    public function create(): void
    {
        $menus = $this->roleModel->getAllMenus();
        $this->view('role/create', [
            'title' => 'Tambah Role',
            'menus' => $menus,
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) $this->redirect('/role/create');
        $this->validateCsrf();

        $validator = new Validator($_POST);
        $validator->required('name', 'Nama Role');
        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect('/role/create');
            return;
        }

        $roleId = $this->roleModel->insert([
            'name'        => $this->input('name'),
            'description' => $this->input('description'),
        ]);

        $menuIds = isset($_POST['menu_ids']) && is_array($_POST['menu_ids'])
            ? array_map('intval', $_POST['menu_ids'])
            : [];
        $this->roleModel->savePermissions((int) $roleId, $menuIds);

        Session::setFlash('success', 'Role berhasil ditambahkan!');
        $this->redirect('/role');
    }

    public function edit($id): void
    {
        $id = (int) $id;

        // Admin tidak bisa diedit
        if ($id === 1) {
            Session::setFlash('error', 'Role <b>admin</b> tidak dapat diedit.');
            $this->redirect('/role');
            return;
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            Session::setFlash('error', 'Role tidak ditemukan.');
            $this->redirect('/role');
            return;
        }

        $menus       = $this->roleModel->getAllMenus();
        $permissions = $this->roleModel->getPermissions($id);

        $this->view('role/edit', [
            'title'       => 'Edit Role',
            'row'         => $role,
            'menus'       => $menus,
            'permissions' => $permissions,
        ]);
    }

    public function update($id): void
    {
        $id = (int) $id;

        if ($id === 1) {
            Session::setFlash('error', 'Role <b>admin</b> tidak dapat diedit.');
            $this->redirect('/role');
            return;
        }

        if (!$this->isPost()) $this->redirect("/role/edit/{$id}");
        $this->validateCsrf();

        $role = $this->roleModel->find($id);
        if (!$role) {
            Session::setFlash('error', 'Role tidak ditemukan.');
            $this->redirect('/role');
            return;
        }

        $this->roleModel->update($id, [
            'name'        => $this->input('name'),
            'description' => $this->input('description'),
        ]);

        $menuIds = isset($_POST['menu_ids']) && is_array($_POST['menu_ids'])
            ? array_map('intval', $_POST['menu_ids'])
            : [];
        $this->roleModel->savePermissions($id, $menuIds);

        Session::setFlash('success', 'Role berhasil diupdate!');
        $this->redirect('/role');
    }

    public function delete($id): void
    {
        $id = (int) $id;

        // Admin tidak bisa dihapus
        if ($id === 1) {
            Session::setFlash('error', 'Role <b>admin</b> tidak dapat dihapus.');
            $this->redirect('/role');
            return;
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            Session::setFlash('error', 'Role tidak ditemukan.');
            $this->redirect('/role');
            return;
        }

        $db = Database::getInstance();
        $count = $db->fetch("SELECT COUNT(*) as total FROM users WHERE role_id = ?", [$id]);
        if (($count['total'] ?? 0) > 0) {
            Session::setFlash('error', 'Role sedang digunakan oleh user. Pindahkan user terlebih dahulu.');
            $this->redirect('/role');
            return;
        }

        $this->roleModel->delete($id);
        Session::setFlash('success', 'Role berhasil dihapus!');
        $this->redirect('/role');
    }
}
