<?php
/**
 * Controller: User Management
 */
class UserController extends BaseController
{
    /** @var User */
    private $userModel;

    public function __construct()
    {
        AuthMiddleware::handle('admin');
        $this->userModel = new User();
    }

    public function index(): void
    {
        $users = $this->userModel->allWithRole();
        $this->view('user/index', [
            'title' => 'Manajemen User',
            'users' => $users,
        ]);
    }

    public function create(): void
    {
        $roles = $this->userModel->getRoles();
        $this->view('user/create', [
            'title' => 'Tambah User',
            'roles' => $roles,
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) $this->redirect('/user/create');
        $this->validateCsrf();

        $validator = new Validator($_POST);
        $validator->required('username', 'Username')
                  ->required('email', 'Email')
                  ->email('email', 'Email')
                  ->required('password', 'Password')
                  ->minLength('password', 6, 'Password')
                  ->required('role_id', 'Role');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect('/user/create');
            return;
        }

        // Cek duplikasi username
        $existingUser = $this->userModel->findByLogin($this->input('username'));
        if ($existingUser) {
            Session::setFlash('error', 'Username sudah digunakan.');
            $this->redirect('/user/create');
            return;
        }

        // Cek duplikasi email
        $existingEmail = $this->userModel->findByLogin($this->input('email'));
        if ($existingEmail) {
            Session::setFlash('error', 'Email sudah digunakan.');
            $this->redirect('/user/create');
            return;
        }

        $this->userModel->insert([
            'username' => $this->input('username'),
            'email'    => $this->input('email'),
            'password' => password_hash($this->input('password'), PASSWORD_BCRYPT, ['cost' => 12]),
            'nama'     => $this->input('nama'),
            'role_id'  => (int) $this->input('role_id'),
            'is_active'=> 1,
        ]);

        Session::setFlash('success', 'User berhasil ditambahkan!');
        $this->redirect('/user');
    }

    public function edit($id): void
    {
        $user = $this->userModel->find((int) $id);
        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('/user');
            return;
        }

        $roles = $this->userModel->getRoles();
        $this->view('user/edit', [
            'title' => 'Edit User',
            'row'   => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id): void
    {
        $id = (int) $id;
        if (!$this->isPost()) $this->redirect("/user/edit/{$id}");
        $this->validateCsrf();

        $user = $this->userModel->find($id);
        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('/user');
            return;
        }

        $username = $this->input('username');
        $email    = $this->input('email');

        // Cek duplikasi username (kecuali user ini sendiri)
        $existingUser = $this->userModel->findByLogin($username);
        if ($existingUser && (int) $existingUser['id'] !== $id) {
            Session::setFlash('error', 'Username sudah digunakan oleh user lain.');
            $this->redirect("/user/edit/{$id}");
            return;
        }

        // Cek duplikasi email (kecuali user ini sendiri)
        $existingEmail = $this->userModel->findByLogin($email);
        if ($existingEmail && (int) $existingEmail['id'] !== $id) {
            Session::setFlash('error', 'Email sudah digunakan oleh user lain.');
            $this->redirect("/user/edit/{$id}");
            return;
        }

        $data = [
            'username' => $username,
            'email'    => $email,
            'nama'     => $this->input('nama'),
            'role_id'  => (int) $this->input('role_id'),
            'is_active'=> (int) $this->input('is_active', 1),
        ];

        $newPassword = $this->input('password', '');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                Session::setFlash('error', 'Password minimal 6 karakter.');
                $this->redirect("/user/edit/{$id}");
                return;
            }
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $this->userModel->update($id, $data);
        Session::setFlash('success', 'User berhasil diupdate!');
        $this->redirect('/user');
    }

    public function delete($id): void
    {
        $id = (int) $id;
        $currentUserId = Auth::user()['id'] ?? null;
        if ($id === (int) $currentUserId) {
            Session::setFlash('error', 'Anda tidak bisa menghapus akun sendiri.');
            $this->redirect('/user');
            return;
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('/user');
            return;
        }

        $this->userModel->delete($id);
        Session::setFlash('success', 'User berhasil dihapus!');
        $this->redirect('/user');
    }

    /**
     * AJAX: Toggle status aktif user
     */
    public function toggleActive($id): void
    {
        $id = (int) $id;
        $currentUserId = Auth::user()['id'] ?? null;
        if ($id === (int) $currentUserId) {
            $this->json(['status' => 'error', 'message' => 'Tidak bisa menonaktifkan akun sendiri.'], 403);
            return;
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $this->json(['status' => 'error', 'message' => 'User tidak ditemukan.'], 404);
            return;
        }

        $newStatus = ($user['is_active'] ?? 1) ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        $this->json([
            'status'    => 'success',
            'is_active' => $newStatus,
            'message'   => $newStatus ? 'Akun diaktifkan!' : 'Akun dinonaktifkan!',
        ]);
    }
}
