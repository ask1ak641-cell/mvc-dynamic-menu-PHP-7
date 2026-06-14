<?php
$pageTitle = $title ?? 'Manajemen Menu';
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
                    <li class="breadcrumb-item active">Menu</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Info -->
        <div class="alert" style="background:#e0e7ff;color:#3730a3;border:none;border-radius:10px;">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Auto-Generate:</strong> Saat membuat menu baru, file Controller, Model, View dan Route akan dibuat otomatis.
        </div>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Daftar Menu</h3>
                <div class="ml-auto">
                    <a href="<?= URL::base('/menu/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Menu
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Menu</th>
                                <th>Icon</th>
                                <th>URL</th>
                                <th>Parent</th>
                                <th width="70">Order</th>
                                <th width="80">Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($menus)): ?>
                            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-bars"></i><p class="text-muted">Belum ada menu</p></div></td></tr>
                            <?php else: ?>
                            <?php $no = 1; foreach ($menus as $menu): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php if ($menu['parent_id']): ?>
                                    <span class="text-muted">&nbsp;&nbsp;↳</span>
                                    <?php endif; ?>
                                    <strong><?= htmlspecialchars($menu['name']) ?></strong>
                                </td>
                                <td><i class="<?= htmlspecialchars($menu['icon']) ?> mr-1"></i><small class="text-muted"><?= htmlspecialchars($menu['icon']) ?></small></td>
                                <td><code style="background:#f1f5f9;padding:2px 8px;border-radius:4px;"><?= htmlspecialchars($menu['url']) ?></code></td>
                                <td><small class="text-muted"><?= htmlspecialchars($menu['parent_name'] ?? '-') ?></small></td>
                                <td><span class="badge badge-secondary"><?= $menu['sort_order'] ?></span></td>
                                <td>
                                    <span class="badge badge-<?= $menu['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $menu['is_active'] ? 'Aktif' : 'Off' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= URL::base('/menu/edit/' . $menu['id']) ?>" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                    <a href="<?= URL::base('/menu/delete/' . $menu['id']) ?>" class="btn btn-danger btn-xs btn-delete" onclick="return confirm('Hapus menu ini? Semua file CRUD terkait juga akan dihapus!')"><i class="fas fa-trash"></i></a>
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
</div>
