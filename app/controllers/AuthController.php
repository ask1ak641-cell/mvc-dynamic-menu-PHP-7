<?php
/**
 * Controller: Auth - Login, Logout
 */
class AuthController extends BaseController
{
    /** @var User */
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Halaman login
     */
    public function loginForm(): void
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', [
            'title' => 'Login - ' . APP_NAME,
            'expired' => isset($_GET['expired']),
        ], 'auth');
    }

    /**
     * Proses login
     */
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $this->validateCsrf();

        $login    = trim($this->input('login', ''));
        $password = $_POST['password'] ?? ''; // Jangan trim password (mungkin ada spasi)

        // Validasi input
        $validator = new Validator($_POST);
        $validator->required('login', 'Username/Email')
                  ->required('password', 'Password');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect('/login');
            return;
        }

        // Cari user
        $user = $this->userModel->findByLogin($login);

        if (!$user) {
            // Pesan generik untuk mencegah enumeration attack
            Session::setFlash('error', 'Username/Email atau Password salah.');
            $this->redirect('/login');
            return;
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            Session::setFlash('error', 'Username/Email atau Password salah.');
            $this->redirect('/login');
            return;
        }

        // Cek apakah user aktif
        if (($user['is_active'] ?? 1) != 1) {
            Session::setFlash('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
            $this->redirect('/login');
            return;
        }

        // Ambil permissions
        $user['permissions'] = $this->userModel->getPermissions($user['role_id']);

        // Login
        Auth::login($user);

        $this->redirect('/dashboard');
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
