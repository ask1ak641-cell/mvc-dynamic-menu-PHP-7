# 🚀 PHP Native MVC Boilerplate

> Starter kit PHP Native (tanpa framework) dengan arsitektur MVC, sistem keamanan tinggi, CRUD Generator otomatis, dan UI modern berbasis AdminLTE.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🔐 **Login & Keamanan** | `password_hash()` BCRYPT, CSRF Token, XSS Filtering, PDO Prepared Statements, Session Management Aman, Auto Logout Idle |
| 👥 **Role & Permission** | 3 Role default (admin, operator, user), middleware akses per halaman, permission per menu |
| 📂 **Dynamic Menu** | Menu disimpan di database, parent-child (multi-level), icon picker FontAwesome, filter berdasarkan role |
| ⚡ **CRUD Generator** | Generate Controller + Model + Views otomatis dari definisi field, termasuk SQL CREATE TABLE |
| 🎨 **UI Modern** | AdminLTE 3 + Bootstrap 4 + FontAwesome 6, Responsive, Toastr notifikasi |
| 🛣️ **Routing** | Clean URL, parameter dinamis `{id}`, tanpa `.php` di URL |
| 📧 **Email** | PHPMailer ready (konfigurasi tinggal isi credential) |
| 🧩 **Helpers** | URL, Session, Auth, Security, Validator, Response |

---

## 📁 Struktur Folder

```
php-mvc-boilerplate/
├── public/                    # Entry point & assets publik
│   ├── index.php              # Semua request masuk ke sini
│   ├── .htaccess              # URL rewriting
│   └── assets/                # CSS, JS, plugins (kosong, bisa diisi custom)
│
├── app/                       # Aplikasi utama
│   ├── config/                # Konfigurasi (app & database)
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/           # Controller (logika bisnis)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   ├── RoleController.php
│   │   ├── MenuController.php
│   │   ├── SiswaController.php     # Contoh modul CRUD
│   │   └── CrudGeneratorController.php
│   ├── models/                # Model (database interaction)
│   │   ├── User.php
│   │   ├── Menu.php
│   │   ├── Role.php
│   │   └── Siswa.php              # Contoh model
│   ├── views/                 # Views (tampilan)
│   │   ├── layouts/           # Template layouts
│   │   ├── auth/              # Login page
│   │   ├── dashboard/         # Dashboard
│   │   ├── menu/              # CRUD Menu
│   │   ├── role/              # CRUD Role
│   │   ├── user/              # CRUD User
│   │   └── siswa/             # Contoh modul CRUD
│   ├── helpers/               # Helper classes
│   │   ├── URL.php
│   │   ├── Session.php
│   │   ├── Auth.php
│   │   ├── Security.php
│   │   ├── Validator.php
│   │   └── Response.php
│   └── middleware/            # Middleware
│       └── AuthMiddleware.php
│
├── system/                    # Core system
│   ├── Database.php           # PDO wrapper (Singleton)
│   ├── Router.php             # URL Router
│   ├── BaseController.php     # Base controller class
│   ├── BaseModel.php          # Base model class (CRUD + pagination)
│   └── CrudGenerator.php      # Auto-generate CRUD files
│
├── install/                   # Installer
│   └── database.sql           # Schema + seed data
│
└── .htaccess                  # Redirect ke public/
```

---

## 🚀 Cara Install

### 1. Clone / Copy Project

```bash
cd /var/www/html    # atau htdocs untuk XAMPP
git clone <repo-url> php-mvc-boilerplate
# atau copy manual folder php-mvc-boilerplate
```

### 2. Konfigurasi Database

Edit file `app/config/database.php`:

```php
define('DB_CONFIG', [
    'host'    => 'localhost',
    'port'    => 3306,
    'name'    => 'php_mvc_boilerplate',
    'user'    => 'root',           // Ganti
    'pass'    => '',               // Ganti
    'charset' => 'utf8mb4',
]);
```

Edit file `app/config/config.php`:

```php
define('APP_URL', 'http://localhost/php-mvc-boilerplate');  // Sesuaikan
```

### 3. Import Database

```bash
# Via MySQL CLI
mysql -u root -p < install/database.sql

# Atau buka phpMyAdmin → import install/database.sql
```

### 4. Jalankan Aplikasi

Buka browser: `http://localhost/php-mvc-boilerplate`

---

## 🔑 Default Login

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Operator | `operator` | `operator123` |
| User | `user` | `user123` |

---

## 📝 Cara Menambah Modul Baru

### Metode 1: CRUD Generator (Otomatis) ⚡

1. Login sebagai **admin**
2. Buka **Tools → CRUD Generator**
3. Isi:
   - **Nama Modul**: `produk` (huruf kecil, tanpa spasi)
   - **Label**: `Data Produk`
   - **Field Definitions**:
     ```
     kode|Kode Produk|text|required|
     nama|Nama Produk|text|required|
     harga|Harga|number|required|
     deskripsi|Deskripsi|textarea||
     kategori|Kategori|select|required|makanan:Makanan,minuman:Minuman
     ```
4. Klik **GENERATE CRUD**
5. Tambahkan routing di `public/index.php`:
   ```php
   $router->get('/produk', 'ProdukController@index');
   $router->get('/produk/create', 'ProdukController@create');
   $router->post('/produk/store', 'ProdukController@store');
   $router->get('/produk/edit/{id}', 'ProdukController@edit');
   $router->post('/produk/edit/{id}', 'ProdukController@update');
   $router->get('/produk/delete/{id}', 'ProdukController@delete');
   ```
6. Jalankan SQL CREATE TABLE yang ditampilkan
7. Tambahkan menu di Manajemen Menu
8. Beri akses di Role Permissions

### Metode 2: Manual

1. Buat Model: `app/models/Produk.php`
   ```php
   class Produk extends BaseModel {
       protected string $table = 'produk';
   }
   ```
2. Buat Controller: `app/controllers/ProdukController.php`
3. Buat Views: `app/views/produk/index.php`, `create.php`, `edit.php`
4. Tambahkan route di `public/index.php`
5. Tambahkan menu + permission

---

## 🔒 Fitur Keamanan

### Password Hashing
Semua password di-hash menggunakan `password_hash()` dengan algoritma **BCRYPT** (cost 12).

```php
// Saat menyimpan
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Saat verifikasi
if (password_verify($password, $user['password'])) { ... }
```

### CSRF Protection
Setiap form POST harus menyertakan CSRF token:

```php
// Di view (otomatis)
<?= Security::csrfField() ?>

// Di controller (validasi)
$this->validateCsrf();
```

### SQL Injection Prevention
Semua query database menggunakan **PDO prepared statements**:

```php
// ✅ Aman
$db->fetch("SELECT * FROM users WHERE id = ?", [$id]);

// ❌ Tidak aman (jangan lakukan ini!)
$db->query("SELECT * FROM users WHERE id = $id");
```

### XSS Protection
Semua output ke HTML melalui `htmlspecialchars()`:

```php
<?= htmlspecialchars($data['nama']) ?>
```

### Auto Logout (Idle Timeout)
Session otomatis berakhir setelah 15 menit tidak ada aktivitas.

---

## 🧩 Helper Reference

### URL Helper
```php
URL::base('/user')          // http://localhost/app/user
URL::asset('css/style.css') // http://localhost/app/public/assets/css/style.css
URL::isActive('/user')      // Cek apakah URL saat ini aktif
```

### Session Helper
```php
Session::set('key', $value);
Session::get('key', 'default');
Session::setFlash('success', 'Pesan sukses!');
Session::getFlash('success');     // Auto-hapus setelah dibaca
```

### Auth Helper
```php
Auth::isLoggedIn()      // Cek login
Auth::user()            // Data user login
Auth::role()            // Role user
Auth::hasRole('admin')  // Cek role
Auth::can('permission') // Cek permission
```

### Security Helper
```php
Security::sanitize($input)   // XSS filter
Security::csrfField()        // Hidden CSRF input
Security::e($string)         // Escape untuk HTML output
```

### Validator
```php
$v = new Validator($_POST);
$v->required('nama', 'Nama')
  ->email('email', 'Email')
  ->minLength('password', 6, 'Password');

if ($v->fails()) {
    $errors = $v->errors();
}
```

### Database (BaseModel)
```php
$model->all()                          // SELECT *
$model->find($id)                      // SELECT by ID
$model->insert(['nama' => '...'])      // INSERT → return ID
$model->update($id, ['nama' => '...']) // UPDATE
$model->delete($id)                    // DELETE
$model->paginate(1, 10)               // Pagination
$model->count()                        // COUNT(*)
```

---

## 🛣️ Routing

Route didaftarkan di `public/index.php`:

```php
// GET routes
$router->get('/path', 'Controller@method');

// POST routes
$router->post('/path', 'Controller@method');

// Dynamic parameter
$router->get('/user/edit/{id}', 'UserController@edit');
// Parameter $id akan otomatis dikirim ke method edit($id)
```

---

## 🎨 UI Framework

- **AdminLTE 3.2** (Bootstrap 4 based)
- **FontAwesome 6** (Free icons)
- **Toastr.js** (Notifications)
- **Select2** (Enhanced select dropdowns)

Semua loaded dari CDN. Bisa diubah ke local file dengan menaruh di `public/assets/`.

---

## 📧 Email (PHPMailer)

Konfigurasi ada di `app/config/config.php`. Untuk mengaktifkan:

1. Install PHPMailer via Composer:
   ```bash
   composer require phpmailer/phpmailer
   ```
2. Atau download manual ke folder `vendor/`
3. Isi credential SMTP di `config.php`

Contoh penggunaan:
```php
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = MAIL_HOST;
$mail->SMTPAuth = true;
$mail->Username = MAIL_USERNAME;
$mail->Password = MAIL_PASSWORD;
$mail->Port = MAIL_PORT;
// ...
```

---

## 🤝 Kontribusi & Pengembangan

Silakan gunakan boilerplate ini sebagai template untuk project-project Anda. Beberapa ide pengembangan:

- [ ] Dark mode toggle
- [ ] Activity log / audit trail
- [ ] Export Excel
- [ ] Import data dari CSV/Excel
- [ ] REST API sederhana
- [ ] Upload file handler
- [ ] Unit testing (PHPUnit)
- [ ] Docker setup

---

## 📄 Lisensi

MIT License — Bebas digunakan untuk project pribadi maupun komersial.

---

**Dibuat dengan ❤️ untuk developer Indonesia**
