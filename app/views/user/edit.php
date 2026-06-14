<?php
$pageTitle = $title ?? 'Edit User';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0"><?= $pageTitle ?></h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL::base('/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= URL::base('/user') ?>">User</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                    <div class="card-header"><h3 class="card-title">Form Edit User</h3></div>
                    <form method="POST" action="<?= URL::base('/user/edit/' . $row['id']) ?>">
                        <div class="card-body">
                            <?= Security::csrfField() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label class="required">Username</label>
                                        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($row['username']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label class="required">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                            </div>
                            <div class="form-group"><label>Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti" minlength="6">
                                <small class="text-muted">Isi hanya jika ingin mengganti password</small>
                            </div>
                            <div class="form-group"><label class="required">Role</label>
                                <select name="role_id" class="form-control select2" required>
                                    <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" <?= $role['id'] == $row['role_id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group"><label>Status Akun</label>
                                <select name="is_active" class="form-control" style="max-width:200px;">
                                    <option value="1" <?= ($row['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= ($row['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Update</button>
                            <a href="<?= URL::base('/user') ?>" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
