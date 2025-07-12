<?php

namespace WJM;

use wpdb;
use WJM\Infra\WordPress\View;
use WJM\Infra\WordPress\AssetsLoader;
use WJM\Infra\WordPress\AdminMenu;
use WJM\Infra\WordPress\UrlHelper;
use WJM\Infra\Repositories\FormRepository;
use WJM\Infra\Services\EmailSender;
use WJM\Domain\Factories\FormFactory;
use WJM\Domain\Factories\FormMessageFactory;
use WJM\Application\UseCase\Form\CreateFormUseCase;
use WJM\Application\UseCase\Form\SubmitFormUseCase;
use WJM\Application\Controllers\DashboardController;
use WJM\Application\Controllers\FormController;
use WJM\Application\Controllers\SubmissionController;
use WJM\Application\Controllers\ExportController;

use WJM\Infra\Hooks\HookRegistrar;

class PluginKernel
{
    public function __construct(private wpdb $wpdb) {}

    public function register(): void
    {
        /** ========== VIEW ========== */
        $view = new View();

        /** ========== SERVICES & REPOSITORIES ========== */
        $formRepository = new FormRepository($this->wpdb);
        $emailSender    = new EmailSender();

        /** ========== HELPERS & FACTORIES ========== */
        $urlHelper          = new UrlHelper(WJM_PLUGIN_FILE);
        $formFactory        = new FormFactory();
        $formMessageFactory = new FormMessageFactory();

        /** ========== USE CASES ========== */
        $createFormUseCase = new CreateFormUseCase($formRepository, $formFactory);
        $submitFormUseCase = new SubmitFormUseCase($formRepository, $emailSender, $formMessageFactory);

        /** ========== CONTROLLERS ========== */
        $dashboardController   = new DashboardController($formRepository, $view, $urlHelper);
        $formEditorController  = new FormController($createFormUseCase, $formRepository, $view);
        $submissionController  = new SubmissionController($submitFormUseCase, $formRepository, $view);
        $exportController      = new ExportController($formRepository);

        /** ========== HOOKS ========== */
        $adminMenu    = new AdminMenu($dashboardController, $formEditorController);
        $assetsLoader = new AssetsLoader();

        (new HookRegistrar($adminMenu, $assetsLoader))->register();

        /** ========== SHORTCODES ========== */
        add_shortcode('contact-form', [$submissionController, 'renderShortcode']);
    }
}
