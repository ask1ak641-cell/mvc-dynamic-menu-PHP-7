<?php ?>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon"><i class="fas fa-layer-group"></i></div>
            <h3><?= APP_NAME ?></h3>
            <p>Silakan login untuk melanjutkan</p>
        </div>
        <div class="login-body">
            <form method="POST" action="<?= URL::base('/login') ?>">
                <?= Security::csrfField() ?>
                <div class="form-group">
                    <label><i class="fas fa-user mr-1"></i> Username / Email</label>
                    <input type="text" name="login" class="form-control"
                           placeholder="Masukkan username atau email" required autofocus>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock mr-1"></i> Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Masukkan password" required>
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-login mt-3">
                    <i class="fas fa-sign-in-alt mr-2"></i> LOGIN
                </button>
            </form>
        </div>
        <div class="login-footer">
            <i class="fas fa-shield-alt mr-1"></i> password_hash · CSRF · PDO Prepared Statements
        </div>
    </div>
</div>
<script>
function togglePassword() {
    var p = document.getElementById('password'), i = document.getElementById('eyeIcon');
    if (p.type === 'password') { p.type = 'text'; i.className = 'fas fa-eye-slash'; }
    else { p.type = 'password'; i.className = 'fas fa-eye'; }
}
</script>
