<?php
$pageTitle = $title ?? 'Manajemen User';
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
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Daftar User</h3>
                <div class="ml-auto">
                    <a href="<?= URL::base('/user/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="110">Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><p class="text-muted">Belum ada user</p></div></td></tr>
                            <?php else: ?>
                            <?php $no = 1; foreach ($users as $u): $isSelf = ($u['id'] == Auth::user()['id']); ?>
                            <tr>
                                <td data-label="#"><?= $no++ ?></td>
                                <td data-label="Username">
                                    <?php if ($isSelf): ?><i class="fas fa-star text-warning mr-1" title="Anda"></i><?php endif; ?>
                                    <strong><?= htmlspecialchars($u['username']) ?></strong>
                                </td>
                                <td data-label="Nama"><?= htmlspecialchars($u['nama'] ?? '-') ?></td>
                                <td data-label="Email"><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                                <td data-label="Role"><span class="badge badge-<?= $u['role_name'] === 'admin' ? 'danger' : 'info' ?>"><?= htmlspecialchars($u['role_name']) ?></span></td>
                                <td data-label="Status">
                                    <?php if ($isSelf): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <label class="switch-toggle" for="sw<?= $u['id'] ?>">
                                            <input type="checkbox" id="sw<?= $u['id'] ?>"
                                                   class="toggle-input"
                                                   data-id="<?= $u['id'] ?>"
                                                   <?= $u['is_active'] ? 'checked' : '' ?>
                                                   onchange="toggleUserStatus(this)">
                                            <span class="slider round"></span>
                                        </label>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi">
                                    <a href="<?= URL::base('/user/edit/' . $u['id']) ?>" class="btn btn-outline-primary btn-xs" title="Edit"><i class="fas fa-edit"></i></a>
                                    <?php if (!$isSelf): ?>
                                    <a href="<?= URL::base('/user/delete/' . $u['id']) ?>" class="btn btn-outline-danger btn-xs btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
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
</div>

<script>
function toggleUserStatus(el) {
    var userId = el.getAttribute('data-id');
    var checked = el.checked;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= URL::base('') ?>/user/toggle-active/' + userId, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        try {
            var res = JSON.parse(xhr.responseText);
            if (res.status === 'success') {
                toastr.success(res.message);
            } else {
                toastr.error(res.message);
                el.checked = !checked;
            }
        } catch(e) {
            toastr.error('Gagal mengubah status.');
            el.checked = !checked;
        }
    };
    xhr.onerror = function() {
        toastr.error('Gagal mengubah status.');
        el.checked = !checked;
    };
    xhr.send('csrf_token=' + encodeURIComponent(getCsrfToken()));
}
</script>
