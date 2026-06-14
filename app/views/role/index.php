<?php
$pageTitle = $title ?? 'Manajemen Role';
if ($msg = Session::getFlash('success')): ?>
    <script>window._toastr_success = '<?= addslashes($msg) ?>';</script>
<?php endif;
if ($msg = Session::getFlash('error')): ?>
    <script>window._toastr_error = '<?= addslashes($msg) ?>';</script>
<?php endif; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0"><?= $pageTitle ?></h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL::base('/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Role</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Daftar Role</h3>
                <div class="ml-auto">
                    <a href="<?= URL::base('/role/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Role
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Nama Role</th>
                            <th>Deskripsi</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($roles)): ?>
                        <tr><td colspan="4"><div class="empty-state"><i class="fas fa-user-tag"></i><p class="text-muted">Belum ada role</p></div></td></tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if ($role['id'] == 1): ?>
                                <span class="badge" style="background:#fee2e2;color:#991b1b;">
                                    <i class="fas fa-crown mr-1"></i> <?= htmlspecialchars($role['name']) ?>
                                </span>
                                <small class="text-muted ml-1">(tidak dapat diubah)</small>
                                <?php else: ?>
                                <span class="badge badge-info"><?= htmlspecialchars($role['name']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($role['description'] ?? '-') ?></td>
                            <td>
                                <?php if ($role['id'] == 1): ?>
                                    <span class="text-muted small"><i class="fas fa-lock mr-1"></i> Terkunci</span>
                                <?php else: ?>
                                    <a href="<?= URL::base('/role/edit/' . $role['id']) ?>" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                    <a href="<?= URL::base('/role/delete/' . $role['id']) ?>" class="btn btn-danger btn-xs btn-delete"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
