<?php
$isAdmin = ((int)$row['id'] === 1);
$pageTitle = $title ?? 'Edit Role';

function _renderMenuEditCheckboxes($menus, $perms, $parentId = 0, $level = 0) {
    foreach ($menus as $m) {
        if ((int)($m['parent_id'] ?? 0) !== $parentId) continue;
        $prefix = $level > 0 ? '↳ ' : '';
        $checked = in_array($m['id'], $perms) ? 'checked' : '';
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
                       <?= $checked ?> <?= $hasChildren ? 'data-parent="1"' : '' ?>>
                <label class="custom-control-label" for="menu_<?= $m['id'] ?>" style="font-weight:400;">
                    <i class="<?= htmlspecialchars($m['icon'] ?? 'fas fa-circle') ?> mr-1" style="width:16px;font-size:12px;"></i>
                    <?= $prefix . htmlspecialchars($m['name']) ?>
                </label>
            </div>
        </div>
        <?php
        _renderMenuEditCheckboxes($menus, $perms, (int)$m['id'], $level + 1);
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
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?php if ($isAdmin): ?>
        <div class="alert" style="background:#fef3c7;color:#92400e;border:none;border-radius:10px;">
            <i class="fas fa-lock mr-2"></i>
            <strong>Role admin tidak dapat diubah.</strong> Ini adalah role sistem dengan akses penuh.
            <a href="<?= URL::base('/role') ?>" class="btn btn-sm btn-outline-warning ml-3">← Kembali</a>
        </div>
        <?php else: ?>
        <form method="POST" action="<?= URL::base('/role/edit/' . $row['id']) ?>">
            <?= Security::csrfField() ?>
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><h3 class="card-title">Form Role</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="required">Nama Role</label>
                                <input type="text" name="name" class="form-control" required
                                       value="<?= htmlspecialchars($row['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Update
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
                            <?php _renderMenuEditCheckboxes($menus, $permissions); ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
