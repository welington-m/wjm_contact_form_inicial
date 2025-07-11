<?php

namespace WJM\Infra\WordPress;

use WJM\Application\Controllers\DashboardController;
use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Infra\Repositories\FormRepository;

class AdminMenu
{
    public static function register(): void
    {
        add_action('admin_menu', function () {
            add_menu_page(
                'WJM Contact',
                'WJM Contact',
                'manage_options',
                'wjm_contact_dashboard',
                [self::class, 'renderDashboard'],
                'dashicons-email-alt',
                25
            );
        });
    }

    public static function renderDashboard(): void
    {
        $repository = new FormRepository($GLOBALS['wpdb']);
        $view = new View();
        $controller = new DashboardController($repository, $view);
        $controller->show();
    }
}
