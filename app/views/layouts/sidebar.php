<?php
$menuModel = new Menu();
$roleId = Auth::user()['role_id'] ?? null;
$menuTree = $menuModel->getMenuTree((int) $roleId);

function renderSidebarMenu($menus) {
    $html = '';
    foreach ($menus as $menu) {
        $hasChildren = !empty($menu['children']);
        $icon = htmlspecialchars($menu['icon'] ?? 'fas fa-circle');
        $name = htmlspecialchars($menu['name']);
        $url  = $hasChildren ? '#' : URL::base(htmlspecialchars($menu['url']));
        $isActive = !$hasChildren && URL::isActive($menu['url']);

        if ($hasChildren) {
            $open = '';
            foreach ($menu['children'] as $c) {
                if (URL::isActive($c['url'])) { $open = ' menu-open'; break; }
            }
            $html .= '<li class="nav-item has-treeview' . $open . '">';
            $html .= '<a href="' . $url . '" class="nav-link">';
            $html .= '<i class="nav-icon ' . $icon . '"></i>';
            $html .= '<p>' . $name . '<i class="right fas fa-angle-left"></i></p>';
            $html .= '</a>';
            $html .= '<ul class="nav nav-treeview">';
            $html .= renderSidebarMenu($menu['children']);
            $html .= '</ul></li>';
        } else {
            $active = $isActive ? ' active' : '';
            $html .= '<li class="nav-item">';
            $html .= '<a href="' . $url . '" class="nav-link' . $active . '">';
            $html .= '<i class="nav-icon ' . $icon . '"></i>';
            $html .= '<p>' . $name . '</p>';
            $html .= '</a></li>';
        }
    }
    return $html;
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= URL::base('/dashboard') ?>" class="brand-link text-center">
        <span class="brand-text font-weight-bold">
            <i class="fas fa-layer-group mr-1"></i><?= APP_NAME ?>
        </span>
    </a>
    <div class="sidebar">
        <!-- User info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div style="width:38px;height:38px;background:rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;">
                    <?= strtoupper(substr(Auth::user()['nama'] ?? 'U', 0, 1)) ?>
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block" style="color:#fff;font-weight:500;">
                    <?= htmlspecialchars(Auth::user()['nama'] ?? 'User') ?>
                </a>
                <small style="color:rgba(255,255,255,.6);"><?= htmlspecialchars(Auth::role()) ?></small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?= renderSidebarMenu($menuTree) ?>
            </ul>
        </nav>
    </div>
</aside>
