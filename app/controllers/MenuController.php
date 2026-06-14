<?php
/**
 * Controller: Dynamic Menu Management
 * Auto-generate basic Controller + Model + View saat menu dibuat
 * Auto-delete file saat menu dihapus
 */
class MenuController extends BaseController
{
    /** @var Menu */
    private $menuModel;

    public function __construct()
    {
        AuthMiddleware::handle();
        $this->menuModel = new Menu();
    }

    public function index(): void
    {
        $menus = $this->menuModel->allFlat();
        $this->view('menu/index', [
            'title' => 'Manajemen Menu',
            'menus' => $menus,
        ]);
    }

    public function create(): void
    {
        $parents = $this->menuModel->getParentMenus();
        
        // Get list of tables from database
        $db = Database::getInstance();
        $tables = $db->getTables();
        
        // Filter out system tables
        $systemTables = ['menus', 'users', 'roles', 'role_permissions', 'password_resets', 'sessions'];
        $userTables = array_filter($tables, function($table) use ($systemTables) {
            return !in_array($table, $systemTables);
        });
        
        $this->view('menu/create', [
            'title'      => 'Tambah Menu',
            'parents'    => $parents,
            'tables'     => array_values($userTables),
        ]);
    }

    /**
     * Simpan menu + auto-generate basic files atau CRUD
     */
    public function store(): void
    {
        if (!$this->isPost()) $this->redirect('/menu/create');
        $this->validateCsrf();

        $validator = new Validator($_POST);
        $validator->required('name', 'Nama Menu')->required('url', 'URL');
        if ($validator->fails()) {
            Session::setFlash('error', $validator->firstError());
            $this->redirect('/menu/create');
            return;
        }

        $url = trim($this->input('url'));
        $moduleName = '';
        if ($url !== '#' && $url !== '/dashboard') {
            $moduleName = trim(str_replace('/', '', str_replace('-', '_', $url)));
        }

        // Cek duplikasi
        if (!empty($moduleName)) {
            // Validasi nama modul: hanya alphanumeric dan underscore
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $moduleName)) {
                Session::setFlash('error', 'Nama modul tidak valid. Gunakan huruf, angka, dan underscore. Harus diawali huruf.');
                $this->redirect('/menu/create');
                return;
            }

            $className = ucfirst($moduleName);
            if (file_exists(APP_DIR . "/controllers/{$className}Controller.php") ||
                file_exists(APP_DIR . "/models/{$className}.php") ||
                is_dir(VIEW_DIR . "/{$moduleName}")) {
                Session::setFlash('error', "Modul <b>{$moduleName}</b> sudah ada! Gunakan nama lain.");
                $this->redirect('/menu/create');
                return;
            }
        }

        $menuId = $this->menuModel->insert([
            'name'      => $this->input('name'),
            'url'       => $url,
            'icon'      => $this->input('icon', 'fas fa-circle'),
            'parent_id' => (int) $this->input('parent_id', 0),
            'sort_order'=> (int) $this->input('sort_order', 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        $msg = 'Menu berhasil ditambahkan!';

        // Beri akses ke admin (role_id = 1)
        $db = Database::getInstance();
        $db->execute("INSERT INTO role_permissions (role_id, menu_id) VALUES (1, ?)", [$menuId]);

        if (!empty($moduleName)) {
            try {
                // Check if user wants to generate CRUD from table
                $useCrud = isset($_POST['use_crud']) && $_POST['use_crud'] === '1';
                $selectedTable = $this->input('table_name', '');
                
                if ($useCrud && !empty($selectedTable)) {
                    // Generate full CRUD from selected table
                    $this->generateCrudModule($moduleName, $selectedTable);
                    $msg .= ' CRUD otomatis dari tabel <b>' . htmlspecialchars($selectedTable) . '</b> telah dibuat.';
                } else {
                    // Generate basic module with tutorial
                    $this->generateBasicModule($moduleName);
                    $msg .= ' File siap. Buka URL menu untuk lihat halaman baru.';
                }
                $this->appendRoute($moduleName);
            } catch (\Exception $e) {
                $msg .= ' (Gagal generate file: ' . $e->getMessage() . ')';
            }
        }

        Session::setFlash('success', $msg);
        $this->redirect('/menu');
    }

    public function edit($id): void
    {
        $menu = $this->menuModel->find((int) $id);
        if (!$menu) {
            Session::setFlash('error', 'Menu tidak ditemukan.');
            $this->redirect('/menu');
            return;
        }
        $parents = $this->menuModel->getParentMenus();
        $this->view('menu/edit', ['title' => 'Edit Menu', 'row' => $menu, 'parents' => $parents]);
    }

    public function update($id): void
    {
        if (!$this->isPost()) $this->redirect("/menu/edit/{$id}");
        $this->validateCsrf();

        $id = (int) $id;
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            Session::setFlash('error', 'Menu tidak ditemukan.');
            $this->redirect('/menu');
            return;
        }

        $parentId = (int) $this->input('parent_id', 0);
        if ($parentId === $id) $parentId = 0;

        $this->menuModel->update($id, [
            'name'      => $this->input('name'),
            'url'       => $this->input('url'),
            'icon'      => $this->input('icon', 'fas fa-circle'),
            'parent_id' => $parentId,
            'sort_order'=> (int) $this->input('sort_order', 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        Session::setFlash('success', 'Menu berhasil diupdate!');
        $this->redirect('/menu');
    }

    /**
     * Hapus menu + hapus file terkait
     */
    public function delete($id): void
    {
        $id = (int) $id;
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            Session::setFlash('error', 'Menu tidak ditemukan.');
            $this->redirect('/menu');
            return;
        }

        $db = Database::getInstance();

        // Cek apakah ada child menu
        $children = $db->fetch("SELECT COUNT(*) as total FROM menus WHERE parent_id = ?", [$id]);
        if (($children['total'] ?? 0) > 0) {
            Session::setFlash('error', 'Hapus sub-menu terlebih dahulu.');
            $this->redirect('/menu');
            return;
        }

        if (!empty($menu['url']) && $menu['url'] !== '#' && $menu['url'] !== '/dashboard') {
            $moduleName = trim(str_replace('/', '', str_replace('-', '_', $menu['url'])));

            // Validasi nama sebelum menghapus file
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $moduleName)) {
                $className = ucfirst($moduleName);

                @unlink(APP_DIR . "/models/{$className}.php");
                @unlink(APP_DIR . "/controllers/{$className}Controller.php");

                $viewDir = VIEW_DIR . "/{$moduleName}";
                if (is_dir($viewDir)) $this->deleteDir($viewDir);

                $this->removeRoute($moduleName);
            }
        }

        // Hapus permissions terkait
        $db->execute("DELETE FROM role_permissions WHERE menu_id = ?", [$id]);

        $this->menuModel->delete($id);
        Session::setFlash('success', 'Menu dan file terkait berhasil dihapus!');
        $this->redirect('/menu');
    }

    // ═══════════════════════════════════════════════════════════
    //  Private Helpers
    // ═══════════════════════════════════════════════════════════

    private function generateBasicModule($name)
    {
        // Validasi nama modul
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) {
            throw new \Exception('Nama modul tidak valid.');
        }

        $cn = ucfirst($name);

        // Model
        $m  = "<?php\n";
        $m .= "/**\n * Model: {$cn}\n */\n";
        $m .= "class {$cn} extends BaseModel\n{\n";
        $m .= "    protected \$table = '';\n";
        $m .= "}\n";
        file_put_contents(APP_DIR . "/models/{$cn}.php", $m);

        // Controller
        $c  = "<?php\n";
        $c .= "/**\n * Controller: {$cn}\n */\n";
        $c .= "class {$cn}Controller extends BaseController\n{\n";
        $c .= "    private \${$name}Model;\n\n";
        $c .= "    public function __construct()\n    {\n";
        $c .= "        AuthMiddleware::handle();\n";
        $c .= "        \$this->{$name}Model = new {$cn}();\n";
        $c .= "    }\n\n";
        $c .= "    public function index()\n    {\n";
        $c .= "        \$this->view('{$name}/index', ['title' => '{$cn}']);\n";
        $c .= "    }\n}\n";
        file_put_contents(APP_DIR . "/controllers/{$cn}Controller.php", $c);

        // View - with comprehensive tutorial
        if (!is_dir(VIEW_DIR . "/{$name}")) mkdir(VIEW_DIR . "/{$name}", 0755, true);

        $v  = "<?php\n\$pageTitle = \$title ?? '{$cn}';\n?>\n";
        $v .= "<div class=\"content-header\">\n    <div class=\"container-fluid\">\n";
        $v .= "        <div class=\"row mb-2\">\n";
        $v .= "            <div class=\"col-sm-6\"><h1 class=\"m-0\"><?= \$pageTitle ?></h1></div>\n";
        $v .= "            <div class=\"col-sm-6\">\n";
        $v .= "                <ol class=\"breadcrumb float-sm-right\">\n";
        $v .= "                    <li class=\"breadcrumb-item\"><a href=\"<?= URL::base('/dashboard') ?>\">Dashboard</a></li>\n";
        $v .= "                    <li class=\"breadcrumb-item active\"><?= \$pageTitle ?></li>\n";
        $v .= "                </ol>\n            </div>\n        </div>\n    </div>\n</div>\n\n";

        // Welcome card
        $v .= "<div class=\"content\">\n    <div class=\"container-fluid\">\n\n";
        $v .= "        <div class=\"card\" style=\"background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; color: #fff;\">\n";
        $v .= "            <div class=\"card-body text-center py-5\">\n";
        $v .= "                <div style=\"font-size:56px;margin-bottom:16px;\"><i class=\"fas fa-rocket\"></i></div>\n";
        $v .= "                <h4 class=\"font-weight-bold\">Ini adalah halaman menu baru</h4>\n";
        $v .= "                <p class=\"mb-0\" style=\"opacity:.85;\">Anda bisa mengatur konten di file-file berikut:</p>\n";
        $v .= "            </div>\n        </div>\n\n";

        // File locations
        $v .= "        <div class=\"row mt-3\">\n";
        $v .= "            <div class=\"col-md-4\">\n";
        $v .= "                <div class=\"card card-outline card-primary h-100\">\n";
        $v .= "                    <div class=\"card-header bg-light\"><h6 class=\"mb-0\"><i class=\"fas fa-eye mr-2 text-primary\"></i>View (Tampilan)</h6></div>\n";
        $v .= "                    <div class=\"card-body\">\n";
        $v .= "                        <code style=\"background:#f1f5f9;padding:4px 12px;border-radius:6px;font-size:12px;\">app/views/{$name}/index.php</code>\n";
        $v .= "                        <p class=\"mt-2 mb-0 small text-muted\">Edit file ini untuk mengubah tampilan halaman.</p>\n";
        $v .= "                    </div>\n                </div>\n            </div>\n";
        $v .= "            <div class=\"col-md-4\">\n";
        $v .= "                <div class=\"card card-outline card-success h-100\">\n";
        $v .= "                    <div class=\"card-header bg-light\"><h6 class=\"mb-0\"><i class=\"fas fa-cog mr-2 text-success\"></i>Controller (Fungsi)</h6></div>\n";
        $v .= "                    <div class=\"card-body\">\n";
        $v .= "                        <code style=\"background:#f1f5f9;padding:4px 12px;border-radius:6px;font-size:12px;\">app/controllers/{$cn}Controller.php</code>\n";
        $v .= "                        <p class=\"mt-2 mb-0 small text-muted\">Tempat logika bisnis. Tambahkan method untuk fungsi CRUD.</p>\n";
        $v .= "                    </div>\n                </div>\n            </div>\n";
        $v .= "            <div class=\"col-md-4\">\n";
        $v .= "                <div class=\"card card-outline card-warning h-100\">\n";
        $v .= "                    <div class=\"card-header bg-light\"><h6 class=\"mb-0\"><i class=\"fas fa-database mr-2 text-warning\"></i>Model (Data)</h6></div>\n";
        $v .= "                    <div class=\"card-body\">\n";
        $v .= "                        <code style=\"background:#f1f5f9;padding:4px 12px;border-radius:6px;font-size:12px;\">app/models/{$cn}.php</code>\n";
        $v .= "                        <p class=\"mt-2 mb-0 small text-muted\">Set <code>\$table = 'nama_tabel'</code> untuk mulai menggunakan BaseModel.</p>\n";
        $v .= "                    </div>\n                </div>\n            </div>\n";
        $v .= "        </div>\n\n";

        // Tutorial section
        $v .= "        <div class=\"card mt-3\">\n";
        $v .= "            <div class=\"card-header bg-light\">\n";
        $v .= "                <h5 class=\"mb-0\"><i class=\"fas fa-book-open mr-2 text-primary\"></i>Tutorial: Menambahkan Fungsi CRUD</h5>\n";
        $v .= "            </div>\n";
        $v .= "            <div class=\"card-body\">\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 1: Buat Tabel Database</h6>\n";
        $v .= "                <p class=\"small text-muted\">Jalankan SQL di phpMyAdmin untuk membuat tabel.</p>\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary mt-3\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 2: Set Nama Tabel di Model</h6>\n";
        $v .= "                <p class=\"small text-muted\">Buka <code>app/models/{$cn}.php</code> dan isi <code>\$table = 'nama_tabel_anda'</code></p>\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary mt-3\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 3: Tambahkan Method di Controller</h6>\n";
        $v .= "                <p class=\"small text-muted\">Buka <code>app/controllers/{$cn}Controller.php</code>, tambahkan method: <code>create()</code>, <code>store()</code>, <code>edit(\$id)</code>, <code>update(\$id)</code>, <code>delete(\$id)</code></p>\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary mt-3\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 4: Tambahkan Routes</h6>\n";
        $v .= "                <p class=\"small text-muted\">Buka <code>public/index.php</code>, tambahkan route CRUD sebelum <code>// ─── Jalankan Router</code></p>\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary mt-3\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 5: Buat View Files</h6>\n";
        $v .= "                <p class=\"small text-muted\">Buat file <code>create.php</code> dan <code>edit.php</code> di <code>app/views/{$name}/</code></p>\n";
        $v .= "                <h6 class=\"font-weight-bold text-primary mt-3\"><i class=\"fas fa-step-forward mr-1\"></i> Langkah 6: Atur Permission</h6>\n";
        $v .= "                <p class=\"small text-muted\">Buka <strong>Manajemen &rarr; Roles</strong>, edit role, centang menu ini pada hak akses.</p>\n";
        $v .= "                <div class=\"alert mt-3\" style=\"background:#e0e7ff;color:#3730a3;border:none;border-radius:10px;\">\n";
        $v .= "                    <i class=\"fas fa-lightbulb mr-2\"></i><strong>Tips:</strong> BaseModel sudah menyediakan method <code>all()</code>, <code>find()</code>, <code>insert()</code>, <code>update()</code>, <code>delete()</code>, <code>paginate()</code>, dan <code>count()</code> secara otomatis.\n";
        $v .= "                </div>\n";
        $v .= "            </div>\n        </div>\n\n";

        $v .= "    </div>\n</div>\n";

        file_put_contents(VIEW_DIR . "/{$name}/index.php", $v);
    }

    private function appendRoute($name)
    {
        $f = PUBLIC_DIR . '/index.php';
        if (!file_exists($f) || !is_writable($f)) return;

        // Validasi nama modul
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) return;

        $content = file_get_contents($f);
        $cn = ucfirst($name);
        $route = "\n// ─── Menu: {$cn} ───────────────────────────\n";
        $route .= "\$router->get('/{$name}', '{$cn}Controller@index');\n";
        $marker = '// ─── Jalankan Router';
        if (strpos($content, $marker) !== false) {
            file_put_contents($f, str_replace($marker, $route . $marker, $content));
        }
    }

    private function removeRoute($name)
    {
        $f = PUBLIC_DIR . '/index.php';
        if (!file_exists($f) || !is_writable($f)) return;

        $content = file_get_contents($f);
        $pattern = "/\/\/ ─── Menu: " . preg_quote(ucfirst($name), '/') . ".*?\n\\\$router->.*?\n/s";
        file_put_contents($f, preg_replace($pattern, '', $content));
    }

    /**
     * Generate CRUD module from database table
     */
    private function generateCrudModule($name, $table)
    {
        // Validasi nama modul
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) {
            throw new \Exception('Nama modul tidak valid.');
        }

        // Use CrudGenerator to create full CRUD
        $gen = new CrudGenerator();
        $gen->setName($name)
            ->setTable($table)
            ->setLabel(ucwords(str_replace('_', ' ', $name)))
            ->setFieldsFromTable($table)
            ->generate();
    }

    private function deleteDir($dir)
    {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $p = $dir . '/' . $item;
            is_dir($p) ? $this->deleteDir($p) : @unlink($p);
        }
        @rmdir($dir);
    }
}
