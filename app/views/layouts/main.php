<?php
/**
 * Layout: Main - Modern Professional Design
 */
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= Session::csrfToken() ?>">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?> | <?= APP_NAME ?></title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #eef2ff;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #334155;
            --text-muted: #94a3b8;
            --radius: 10px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.04);
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.03);
        }

        * { font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
        body { background: var(--bg); font-size: 14px; color: var(--text); }

        /* Navbar */
        .main-header {
            background: var(--card) !important;
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            height: 58px;
        }
        .main-header .nav-link { color: var(--text) !important; font-weight: 500; font-size: 13px; }
        .main-header .nav-link:hover { color: var(--primary) !important; }

        /* Sidebar */
        .main-sidebar { background: var(--sidebar-bg) !important; }
        .brand-link {
            padding: 18px 16px;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: -.3px;
            background: rgba(255,255,255,.03) !important;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar .user-panel { border-bottom: 1px solid rgba(255,255,255,.06); padding-bottom: 14px; }
        .sidebar .nav-sidebar .nav-link {
            color: #94a3b8 !important;
            border-radius: 8px;
            margin: 1px 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 500;
            transition: all .15s;
        }
        .sidebar .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,.06) !important;
            color: #e2e8f0 !important;
        }
        .sidebar .nav-sidebar .nav-link.active {
            background: var(--primary) !important;
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(99,102,241,.3);
        }
        .sidebar .nav-treeview .nav-link { padding-left: 30px; font-size: 12.5px; }
        .sidebar .nav-icon { font-size: 15px; width: 20px; text-align: center; }
        .sidebar .user-panel .info a { font-size: 13px; }

        /* Content */
        .content-wrapper { background: var(--bg); }
        .content-header { padding: 20px 24px 8px; }
        .content-header h1 { font-weight: 700; font-size: 1.45rem; color: #0f172a; }
        .content { padding: 0 8px; }

        /* Cards */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            background: var(--card);
            overflow: hidden;
        }
        .card-header {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
        }
        .card-header .card-title { font-weight: 600; font-size: 15px; color: #0f172a; }
        .card-body { padding: 20px; }
        .card-footer {
            background: #fafbfc;
            border-top: 1px solid var(--border);
            padding: 12px 20px;
        }
        .card-outline.card-primary { border-top: 3px solid var(--primary); }
        .card-outline.card-success { border-top: 3px solid #10b981; }
        .card-outline.card-warning { border-top: 3px solid #f59e0b; }
        .card-outline.card-danger  { border-top: 3px solid #ef4444; }

        /* Table */
        .table thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
            border-bottom: 1px solid var(--border);
            padding: 10px 14px;
        }
        .table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-color: #f1f5f9;
            font-size: 13px;
        }
        .table tbody tr:hover { background: #f8fafc; }
        .table { margin-bottom: 0; }

        /* Buttons */
        .btn {
            border-radius: 7px;
            font-weight: 500;
            padding: 7px 16px;
            font-size: 13px;
            transition: all .15s;
        }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); box-shadow: 0 2px 8px rgba(99,102,241,.25); }
        .btn-xs { padding: 3px 9px; font-size: 11px; border-radius: 6px; }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        .btn-outline-danger { border-color: #fecaca; color: #dc2626; }
        .btn-outline-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
        .btn-outline-secondary { border-color: var(--border); color: var(--text-muted); }
        .btn-outline-secondary:hover { background: #f1f5f9; }

        /* Badge */
        .badge { padding: 4px 10px; border-radius: 6px; font-weight: 500; font-size: 11px; letter-spacing: .2px; }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-secondary { background: #f1f5f9; color: #64748b; }

        /* Form */
        .form-control {
            border-radius: 7px;
            border: 1px solid var(--border);
            padding: 8px 12px;
            font-size: 13px;
            transition: all .15s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.08);
        }
        label { font-weight: 500; color: #475569; font-size: 13px; margin-bottom: 5px; }
        .required:after { content: ' *'; color: #ef4444; }
        select.form-control { padding: 7px 12px; }

        /* Breadcrumb */
        .breadcrumb { background: none; padding: 0; margin: 0; font-size: 13px; }

        /* Small box */
        .small-box {
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: transform .15s, box-shadow .15s;
            border: 1px solid var(--border);
        }
        .small-box:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.06); }

        /* Alert */
        .alert { border: none; border-radius: var(--radius-sm); font-size: 13px; }

        /* Switch Toggle (custom, rapi) */
        .switch-toggle { position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; }
        .switch-toggle .toggle-input { opacity: 0; width: 0; height: 0; }
        .switch-toggle .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #cbd5e1;
            transition: .2s;
            border-radius: 24px;
        }
        .switch-toggle .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            transition: .2s;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0,0,0,.15);
        }
        .switch-toggle .toggle-input:checked + .slider { background: #6366f1; }
        .switch-toggle .toggle-input:checked + .slider:before { transform: translateX(20px); }
        .switch-toggle .toggle-input:focus + .slider { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }

        /* Pagination */
        .pagination .page-link { border-radius: 7px; margin: 0 3px; border: 1px solid var(--border); color: var(--text); font-weight: 500; font-size: 13px; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* Footer */
        .main-footer { background: var(--card); border-top: 1px solid var(--border); padding: 12px 20px; font-size: 12px; color: var(--text-muted); }

        /* Empty state */
        .empty-state { text-align: center; padding: 40px 20px; }
        .empty-state i { font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 10px; }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .content-header { padding: 14px 14px 4px; }
            .content-header h1 { font-size: 1.2rem; }
            .card-body { padding: 14px; }
            .btn { padding: 6px 12px; font-size: 12px; }
            .table thead { display: none; }
            .table tbody td {
                display: block;
                text-align: right;
                padding: 8px 12px;
                border: none;
                border-bottom: 1px solid #f1f5f9;
            }
            .table tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--text-muted);
                text-transform: uppercase;
                font-size: 10px;
            }
            .table tbody tr {
                display: block;
                border: 1px solid var(--border);
                border-radius: 8px;
                margin-bottom: 8px;
                background: #fff;
            }
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .card { animation: fadeIn .3s ease-out; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <!-- ========== NAVBAR ========== -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?= URL::base('/dashboard') ?>" class="nav-link">Home</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <div class="d-inline-block text-right mr-2" style="line-height:1.2;">
                        <div style="font-weight:600;font-size:14px;"><?= htmlspecialchars(Auth::user()['nama'] ?? 'User') ?></div>
                        <small class="text-muted" style="font-size:11px;"><?= htmlspecialchars(Auth::role()) ?></small>
                    </div>
                    <i class="fas fa-user-circle fa-lg"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0" style="border-radius:10px;">
                    <span class="dropdown-item-text">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt mr-1"></i> <?= htmlspecialchars(Auth::role()) ?>
                        </small>
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="<?= URL::base('/logout') ?>" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- ========== SIDEBAR ========== -->
    <?php require VIEW_DIR . '/layouts/sidebar.php'; ?>

    <!-- ========== CONTENT ========== -->
    <div class="content-wrapper" style="min-height: 80vh;">
        <?= $content ?>
    </div>

    <!-- ========== FOOTER ========== -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <span class="badge" style="background:var(--primary);color:#fff;">v<?= APP_VERSION ?></span>
        </div>
        <strong><?= APP_NAME ?></strong> &copy; <?= date('Y') ?>
    </footer>
</div>

<!-- ========== SCRIPTS ========== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: '3500',
    showDuration: '300',
    hideDuration: '800',
    newestOnTop: true
};

$(function() {
    if (window._toastr_success) toastr.success(window._toastr_success);
    if (window._toastr_error)   toastr.error(window._toastr_error);

    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

    $('.btn-delete').on('click', function(e) {
        if (!confirm('Yakin ingin menghapus? Tindakan ini tidak bisa dibatalkan.')) {
            e.preventDefault();
        }
    });
});

// CSRF helper untuk AJAX
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}
</script>

</body>
</html>
