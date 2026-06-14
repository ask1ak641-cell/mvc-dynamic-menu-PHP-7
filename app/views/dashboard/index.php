<?php
/**
 * View: Dashboard - Professional & Clean
 */
$pageTitle = $title ?? 'Dashboard';
if ($msg = Session::getFlash('success')): ?>
    <script>window._toastr_success = '<?= addslashes($msg) ?>';</script>
<?php endif;
if ($msg = Session::getFlash('error')): ?>
    <script>window._toastr_error = '<?= addslashes($msg) ?>';</script>
<?php endif; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Dashboard</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Welcome -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; color: #fff;">
                    <div class="card-body d-flex align-items-center py-4">
                        <div style="font-size: 42px; margin-right: 20px;"><i class="fas fa-hand-sparkles"></i></div>
                        <div>
                            <h4 class="mb-1" style="font-weight: 700;">Selamat datang, <?= htmlspecialchars(Auth::user()['nama'] ?? 'User') ?>!</h4>
                            <p class="mb-0" style="opacity: .85;">
                                Anda login sebagai <span style="background: rgba(255,255,255,.2); padding: 2px 10px; border-radius: 20px; font-weight: 600;"><?= htmlspecialchars(Auth::role()) ?></span>
                            </p>
                        </div>
                        <div class="ml-auto d-none d-md-block text-right" style="opacity: .7; font-size: 13px;">
                            <div><?= APP_NAME ?> v<?= APP_VERSION ?></div>
                            <div><?= date('l, d F Y') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box" style="background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.06);">
                    <div class="inner" style="padding: 20px;">
                        <div class="d-flex align-items-center">
                            <div style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                                <i class="fas fa-users" style="color: #4f46e5; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" style="font-weight: 700; color: #1e293b;"><?= $stats['total_users'] ?? 0 ?></h3>
                                <p class="mb-0 text-muted" style="font-size: 13px;">Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box" style="background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.06);">
                    <div class="inner" style="padding: 20px;">
                        <div class="d-flex align-items-center">
                            <div style="width: 48px; height: 48px; background: #d1fae5; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                                <i class="fas fa-user-tag" style="color: #10b981; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" style="font-weight: 700; color: #1e293b;"><?= $stats['total_roles'] ?? 0 ?></h3>
                                <p class="mb-0 text-muted" style="font-size: 13px;">Total Roles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box" style="background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.06);">
                    <div class="inner" style="padding: 20px;">
                        <div class="d-flex align-items-center">
                            <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                                <i class="fas fa-bars" style="color: #f59e0b; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" style="font-weight: 700; color: #1e293b;"><?= $stats['total_menus'] ?? 0 ?></h3>
                                <p class="mb-0 text-muted" style="font-size: 13px;">Total Menu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row mt-2">
            <div class="col-md-4">
                <div class="card card-outline card-primary" style="cursor: pointer;" onclick="location.href='<?= URL::base('/menu/create') ?>'">
                    <div class="card-body text-center py-4">
                        <div style="font-size: 32px; margin-bottom: 8px; color: #6366f1;"><i class="fas fa-plus-circle"></i></div>
                        <h6 class="font-weight-bold mb-1">Buat Menu Baru</h6>
                        <small class="text-muted">Auto-generate halaman baru</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-success" style="cursor: pointer;" onclick="location.href='<?= URL::base('/user/create') ?>'">
                    <div class="card-body text-center py-4">
                        <div style="font-size: 32px; margin-bottom: 8px; color: #10b981;"><i class="fas fa-user-plus"></i></div>
                        <h6 class="font-weight-bold mb-1">Tambah User</h6>
                        <small class="text-muted">Buat akun baru</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-warning" style="cursor: pointer;" onclick="location.href='<?= URL::base('/role/create') ?>'">
                    <div class="card-body text-center py-4">
                        <div style="font-size: 32px; margin-bottom: 8px; color: #f59e0b;"><i class="fas fa-key"></i></div>
                        <h6 class="font-weight-bold mb-1">Tambah Role</h6>
                        <small class="text-muted">Atur hak akses</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
