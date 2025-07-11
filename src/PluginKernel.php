<?php

namespace WJM;

use WJM\Infra\WordPress\AssetsLoader;
use WJM\Infra\WordPress\AdminMenu;
use WJM\Infra\Repositories\FormRepository;
use WJM\Infra\Services\EmailSender;
use WJM\Infra\WordPress\View;

use WJM\Application\UseCase\Form\CreateFormUseCase;
use WJM\Application\UseCase\Form\SubmitFormUseCase;
use WJM\Domain\Factories\FormFactory;
use WJM\Domain\Factories\FormMessageFactory;

use WJM\Application\Controllers\SubmissionController;
use WJM\Application\Controllers\DashboardController;
use WJM\Application\Controllers\ExportController;
use WJM\Application\Controllers\FormController;
use WJM\Infra\Hooks\HookRegistrar;
use wpdb;

class PluginKernel
{
    public function __construct(
        private wpdb $wpdb
    ) {}

    public function register(): void
    {
        // View Renderer
        $view = new View();

        // Repository e Services
        $formRepository = new FormRepository($this->wpdb);
        $emailSender = new EmailSender();

        // Factories
        $formFactory = new FormFactory();
        $formMessageFactory = new FormMessageFactory();

        // UseCases
        $createFormUseCase = new CreateFormUseCase($formRepository, $formFactory);
        $submitFormUseCase = new SubmitFormUseCase($formRepository, $emailSender, $formMessageFactory);

        // Controllers
        $dashboardController = new DashboardController($formRepository, $view);
        $formEditorController = new FormController($createFormUseCase, $formRepository, $view);
        $submissionController = new SubmissionController($submitFormUseCase, $formRepository, $view);
        $exportController = new ExportController($formRepository);

        // Menu e Assets
        $adminMenu = new AdminMenu($dashboardController, $formEditorController);
        $assetsLoader = new AssetsLoader();

        (new HookRegistrar($adminMenu, $assetsLoader))->register();

        // Shortcode
        add_shortcode('contact-form', [$submissionController, 'renderShortcode']);
    }
}
