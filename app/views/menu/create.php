<?php
$pageTitle = $title ?? 'Tambah Menu';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0"><?= $pageTitle ?></h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL::base('/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= URL::base('/menu') ?>">Menu</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form method="POST" action="<?= URL::base('/menu/store') ?>">
            <?= Security::csrfField() ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Form Menu Baru</h3></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="required">Nama Menu</label>
                                        <input type="text" name="name" class="form-control" required
                                               placeholder="Contoh: Data Produk" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="required">URL</label>
                                        <input type="text" name="url" class="form-control" required
                                               placeholder="/produk">
                                        <small class="text-muted">Contoh: <code>/produk</code></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="icon_preview" style="min-width:42px;justify-content:center;">
                                                    <i class="fas fa-circle"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="icon" id="icon_input" class="form-control"
                                                   value="fas fa-circle">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#iconModal">
                                                    <i class="fas fa-icons"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Parent Menu</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="0">-- Root --</option>
                                            <?php foreach ($parents as $p): ?>
                                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Urutan</label>
                                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="pt-1">
                                            <label class="switch-toggle">
                                                <input type="checkbox" name="is_active" value="1" checked class="toggle-input">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Simpan Menu
                            </button>
                            <a href="<?= URL::base('/menu') ?>" class="btn btn-outline-secondary ml-2">Batal</a>
                            <small class="text-muted ml-auto">
                                <i class="fas fa-magic mr-1"></i> Model, View, Controller & Route dibuat otomatis
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>File yang Dibuat</h3>
                        </div>
                        <div class="card-body py-2">
                            <table class="table table-sm table-borderless mb-0" style="font-size:12px;">
                                <tr><td style="width:22px;"><span class="badge badge-primary">M</span></td><td><code>app/models/Nama.php</code></td></tr>
                                <tr><td><span class="badge badge-success">C</span></td><td><code>app/controllers/NamaController.php</code></td></tr>
                                <tr><td><span class="badge badge-info">V</span></td><td><code>app/views/nama/index.php</code></td></tr>
                                <tr><td><span class="badge badge-warning">R</span></td><td>Route di <code>index.php</code></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h3 class="card-title"><i class="fas fa-lightbulb mr-2"></i>Tips</h3>
                        </div>
                        <div class="card-body py-2">
                            <ul class="pl-3 mb-0" style="font-size:12px;line-height:2;">
                                <li>Nama harus <b>unik</b></li>
                                <li>URL diawali <code>/</code></li>
                                <li>Parent pilih "-- Root" untuk menu utama</li>
                                <li>Setelah simpan, buka URL untuk lihat hasil</li>
                                <li>Edit konten di <code>views/namamenu/</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:12px;border:none;">
            <div class="modal-header bg-light" style="border-radius:12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-icons mr-2"></i>Pilih Icon</h5>
                <div class="d-flex align-items-center">
                    <input type="text" class="form-control form-control-sm mr-2" id="iconSearch" placeholder="Cari icon..." style="width:180px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body" style="max-height:420px;overflow-y:auto;">
                <div id="modalIconGrid" style="line-height:0;">
                    <?php
                    $allIcons = [
                        'fas fa-home','fas fa-tachometer-alt','fas fa-th','fas fa-bars',
                        'fas fa-users','fas fa-user','fas fa-user-plus','fas fa-user-edit','fas fa-user-friends',
                        'fas fa-user-graduate','fas fa-user-shield','fas fa-user-cog','fas fa-user-check',
                        'fas fa-cog','fas fa-cogs','fas fa-wrench','fas fa-tools',
                        'fas fa-database','fas fa-server','fas fa-cloud','fas fa-download',
                        'fas fa-file','fas fa-file-alt','fas fa-file-invoice','fas fa-file-pdf','fas fa-file-excel',
                        'fas fa-folder','fas fa-folder-open','fas fa-folder-plus','fas fa-clipboard',
                        'fas fa-clipboard-list','fas fa-tasks','fas fa-list-alt',
                        'fas fa-chart-bar','fas fa-chart-line','fas fa-chart-pie','fas fa-table',
                        'fas fa-book','fas fa-book-open','fas fa-graduation-cap','fas fa-school',
                        'fas fa-calendar-alt','fas fa-clock','fas fa-bell','fas fa-envelope','fas fa-inbox',
                        'fas fa-paper-plane','fas fa-comment','fas fa-comments','fas fa-phone',
                        'fas fa-address-card','fas fa-id-card','fas fa-map','fas fa-map-marker-alt',
                        'fas fa-globe','fas fa-building','fas fa-store','fas fa-warehouse','fas fa-industry',
                        'fas fa-shopping-cart','fas fa-credit-card','fas fa-money-bill','fas fa-coins',
                        'fas fa-truck','fas fa-shipping-fast','fas fa-box','fas fa-boxes',
                        'fas fa-tag','fas fa-tags','fas fa-barcode','fas fa-qrcode','fas fa-gift',
                        'fas fa-star','fas fa-heart','fas fa-thumbs-up','fas fa-award','fas fa-trophy','fas fa-medal',
                        'fas fa-crown','fas fa-shield-alt','fas fa-lock','fas fa-key',
                        'fas fa-sign-in-alt','fas fa-sign-out-alt','fas fa-ban','fas fa-check-circle',
                        'fas fa-exclamation-triangle','fas fa-info-circle',
                        'fas fa-check','fas fa-times','fas fa-plus','fas fa-edit',
                        'fas fa-trash-alt','fas fa-save','fas fa-search','fas fa-filter',
                        'fas fa-sync','fas fa-undo','fas fa-share','fas fa-link',
                        'fas fa-copy','fas fa-pencil-alt','fas fa-paint-brush',
                        'fas fa-image','fas fa-camera','fas fa-video',
                        'fas fa-music','fas fa-play','fas fa-power-off',
                        'fas fa-bolt','fas fa-fire','fas fa-leaf','fas fa-code','fas fa-terminal',
                        'fas fa-magic','fas fa-rocket','fas fa-layer-group','fas fa-puzzle-piece',
                        'fas fa-circle','fas fa-dot-circle','fas fa-check-square','fas fa-spinner',
                    ];
                    $allIcons = array_unique($allIcons);
                    foreach ($allIcons as $icon):
                    ?>
                    <div class="modal-icon-item" data-icon="<?= $icon ?>" title="<?= $icon ?>">
                        <i class="<?= $icon ?>"></i>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted mr-auto"><?= count($allIcons) ?> icons</small>
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Pilih</button>
            </div>
        </div>
    </div>
</div>

<style>
.modal-icon-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    margin: 3px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    transition: all .12s;
    color: #64748b;
    vertical-align: top;
}
.modal-icon-item:hover {
    background: #6366f1;
    color: #fff;
    border-color: #6366f1;
    transform: scale(1.08);
}
.modal-icon-item.selected {
    background: #6366f1;
    color: #fff;
    border-color: #6366f1;
}
</style>

<script>
(function w() { if (typeof jQuery === 'undefined') { setTimeout(w, 50); return; }
$(function() {
    $('#modalIconGrid').on('click', '.modal-icon-item', function() {
        var icon = $(this).data('icon');
        $('#icon_input').val(icon);
        $('#icon_preview i').attr('class', icon);
        $('.modal-icon-item').removeClass('selected');
        $(this).addClass('selected');
    });
    $('#iconSearch').on('keyup', function() {
        var q = $(this).val().toLowerCase();
        $('.modal-icon-item').each(function() {
            $(this).toggle(($(this).data('icon') || '').toLowerCase().indexOf(q) > -1);
        });
    });
    $('#iconModal').on('show.bs.modal', function() {
        var cur = $('#icon_input').val();
        $('.modal-icon-item').removeClass('selected');
        $('.modal-icon-item[data-icon="' + cur + '"]').addClass('selected');
        $('#iconSearch').val('').trigger('keyup');
    });
    $('#icon_input').on('input', function() {
        $('#icon_preview i').attr('class', $(this).val());
    });
});
})();
</script>
