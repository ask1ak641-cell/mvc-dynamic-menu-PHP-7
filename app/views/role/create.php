<?php
$pageTitle = $title ?? 'Tambah Role';

function _renderMenuCheckboxes($menus, $parentId = 0, $level = 0) {
    foreach ($menus as $m) {
        if ((int)($m['parent_id'] ?? 0) !== $parentId) continue;
        $prefix = $level > 0 ? '↳ ' : '';
        $hasChildren = false;
        foreach ($menus as $c) {
            if ((int)($c['parent_id'] ?? 0) === (int)$m['id']) { $hasChildren = true; break; }
        }
        $indent = $level * 20;
        ?>
        <div class="menu-tree-item" style="padding-left:<?= $indent ?>px;">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="menu_ids[]" value="<?= $m['id'] ?>"
                       class="custom-control-input menu-check" id="menu_<?= $m['id'] ?>"
                       <?= $hasChildren ? 'data-parent="1"' : '' ?>>
                <label class="custom-control-label" for="menu_<?= $m['id'] ?>" style="font-weight:400;">
                    <i class="<?= htmlspecialchars($m['icon'] ?? 'fas fa-circle') ?> mr-1" style="width:16px;font-size:12px;"></i>
                    <?= $prefix . htmlspecialchars($m['name']) ?>
                    <?= $hasChildren ? '<small class="text-muted">(parent)</small>' : '' ?>
                </label>
            </div>
        </div>
        <?php
        _renderMenuCheckboxes($menus, (int)$m['id'], $level + 1);
    }
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0"><?= $pageTitle ?></h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL::base('/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= URL::base('/role') ?>">Role</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form method="POST" action="<?= URL::base('/role/store') ?>">
            <?= Security::csrfField() ?>
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><h3 class="card-title">Form Role</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="required">Nama Role</label>
                                <input type="text" name="name" class="form-control" required
                                       placeholder="Contoh: editor" autofocus>
                                <small class="text-muted">Huruf kecil, tanpa spasi</small>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"
                                          placeholder="Deskripsi singkat..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                            <a href="<?= URL::base('/role') ?>" class="btn btn-outline-secondary ml-2">Batal</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-outline card-success">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0"><i class="fas fa-check-square mr-2"></i>Hak Akses Menu</h3>
                            <div class="ml-auto">
                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="$('.menu-check').prop('checked',true)">Check All</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary ml-1" onclick="$('.menu-check').prop('checked',false)">Uncheck All</button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height:420px;overflow-y:auto;">
                            <?php _renderMenuCheckboxes($menus); ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
