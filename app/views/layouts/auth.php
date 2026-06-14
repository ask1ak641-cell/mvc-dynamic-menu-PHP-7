<?php
/**
 * Layout: Auth - Modern login layout
 */
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Login') ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,.15) 0%, transparent 50%),
                              radial-gradient(ellipse at 80% 20%, rgba(16,185,129,.08) 0%, transparent 50%);
            padding: 20px;
        }
        .login-wrapper { width: 100%; max-width: 400px; }
        .login-card {
            background: rgba(255,255,255,.97);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.4);
            backdrop-filter: blur(10px);
        }
        .login-header {
            padding: 36px 28px 28px;
            text-align: center;
            background: #fff;
        }
        .login-header .logo-icon {
            width: 50px;
            height: 50px;
            background: #eef2ff;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #6366f1;
            margin-bottom: 14px;
        }
        .login-header h3 { font-weight: 700; margin: 0; font-size: 20px; color: #0f172a; letter-spacing: -.3px; }
        .login-header p { color: #94a3b8; margin: 6px 0 0; font-size: 13px; }
        .login-body { padding: 0 28px 28px; }
        .form-group label { font-weight: 500; font-size: 12px; color: #64748b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .5px; }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            transition: all .15s;
            background: #f8fafc;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.08);
            background: #fff;
        }
        .btn-login {
            background: #0f172a;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .2px;
            transition: all .15s;
            width: 100%;
            color: #fff;
        }
        .btn-login:hover {
            background: #1e293b;
            box-shadow: 0 4px 15px rgba(0,0,0,.2);
        }
        .login-footer {
            background: #fafbfc;
            padding: 12px 28px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .input-group-text {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: none;
            cursor: pointer;
            border-radius: 0 8px 8px 0;
        }
        .input-group .form-control {
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
    </style>
</head>
<body>

<?= $content ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script>
toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-center', timeOut: '3000' };
$(function() {
    <?php if ($msg = Session::getFlash('success')): ?>
        toastr.success('<?= addslashes($msg) ?>');
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('error')): ?>
        toastr.error('<?= addslashes($msg) ?>');
    <?php endif; ?>
    <?php if (!empty($expired)): ?>
        toastr.warning('Sesi berakhir karena idle. Silakan login kembali.');
    <?php endif; ?>
});
</script>
</body>
</html>
