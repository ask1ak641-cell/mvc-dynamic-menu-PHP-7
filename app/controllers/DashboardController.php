<?php
/**
 * Controller: Dashboard
 */
class DashboardController extends BaseController
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $db = Database::getInstance();
        $stats = [
            'total_users' => 0,
            'total_menus' => 0,
            'total_roles' => 0,
        ];

        try {
            $result = $db->fetch("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = (int) ($result['total'] ?? 0);
        } catch (\Exception $e) {
            // Tabel mungkin belum ada - log error
            error_log("Dashboard stats error (users): " . $e->getMessage());
        }

        try {
            $result = $db->fetch("SELECT COUNT(*) as total FROM menus");
            $stats['total_menus'] = (int) ($result['total'] ?? 0);
        } catch (\Exception $e) {
            error_log("Dashboard stats error (menus): " . $e->getMessage());
        }

        try {
            $result = $db->fetch("SELECT COUNT(*) as total FROM roles");
            $stats['total_roles'] = (int) ($result['total'] ?? 0);
        } catch (\Exception $e) {
            error_log("Dashboard stats error (roles): " . $e->getMessage());
        }

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
        ]);
    }
}
