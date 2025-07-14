<?php

namespace WJM\Infra\WordPress;

use WJM\Application\Controllers\DashboardController;
use WJM\Application\Controllers\FormController;

class AdminMenu
{
    public function __construct(
        private DashboardController $dashboardController,
        private FormController $formController
    ) {}
    public function register(): void
    {
        add_action('admin_menu', function () {
            add_menu_page(
                'WJM Contact Form',
                'WJM Contact Form',
                'manage_options',
                'wjm_contact_dashboard',
                [$this->dashboardController, 'show'],
                // [self::class, 'renderDashboard'],
                'dashicons-email-alt',
                25
            );

            add_submenu_page(
                'wjm_contact_dashboard',
                'Form Editor',
                'Form Editor',
                'manage_options',
                'wjm_form_editor',
                [$this->formController, 'edit']
            );
        });
    }
}
