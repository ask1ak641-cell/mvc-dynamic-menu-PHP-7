<?php
$pageTitle = $title ?? 'Tambah User';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0"><?= $pageTitle ?></h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL::base('/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= URL::base('/user') ?>">User</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="card card-outline card-primary">
                    <div class="card-header"><h3 class="card-title">Form Tambah User</h3></div>
                    <form method="POST" action="<?= URL::base('/user/store') ?>">
                        <div class="card-body">
                            <?= Security::csrfField() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label class="required">Username</label>
                                        <input type="text" name="username" class="form-control" required placeholder="Username login" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label class="required">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                            </div>
                            <div class="form-group"><label class="required">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter" minlength="6">
                                <small class="text-muted">Password di-hash dengan <code>password_hash()</code> BCRYPT</small>
                            </div>
                            <div class="form-group"><label class="required">Role</label>
                                <select name="role_id" class="form-control select2" required>
                                    <option value="">-- Pilih Role --</option>
                                    <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Simpan</button>
                            <a href="<?= URL::base('/user') ?>" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card" style="background:linear-gradient(135deg,#1e1b4b,#312e81);color:#fff;border:none;">
                    <div class="card-body">
                        <h5><i class="fas fa-shield-alt mr-2"></i>Keamanan</h5>
                        <ul class="mb-0 pl-3" style="opacity:.9;font-size:13px;line-height:2;">
                            <li><code style="color:#c7d2fe;">password_hash()</code> BCRYPT cost 12</li>
                            <li><code style="color:#c7d2fe;">password_verify()</code> saat login</li>
                            <li>CSRF token di setiap form POST</li>
                            <li>Session fixation prevention</li>
                            <li>Auto-logout 15 menit idle</li>
                            <li>PDO prepared statements</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
