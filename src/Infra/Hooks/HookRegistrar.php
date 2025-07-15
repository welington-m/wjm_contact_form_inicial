<?php

namespace WJM\Infra\Hooks;

use WJM\Application\Controllers\FormController;
use WJM\Application\Controllers\SubmissionController;
use WJM\Infra\WordPress\AdminMenu;
use WJM\Infra\WordPress\AssetsLoader;

class HookRegistrar
{
    private AdminMenu $adminMenu;
    private AssetsLoader $assetsLoader;
    private FormController $formController;
    private SubmissionController $submissionController;

    public function __construct(
        AdminMenu $adminMenu,
        AssetsLoader $assetsLoader,
        FormController $formController,
        SubmissionController $submissionController
    ) {
        $this->adminMenu = $adminMenu;
        $this->assetsLoader = $assetsLoader;
        $this->formController = $formController;
        $this->submissionController = $submissionController;
    }

    public function register(): void
    {
        $this->adminMenu->register();
        $this->assetsLoader->register();

        // Aqui você pode adicionar outros hooks globais
        // add_action('init', [$this, 'algumaFuncaoGlobal']);
        add_action('admin_post_wjm_save_form', [$this->formController, 'handleSave']);
        add_action('admin_post_wjm_delete_form', [$this->formController, 'delete']);
        add_shortcode('wjm_form', [$this->submissionController, 'renderShortcode']);
        add_action('admin_post_wjm_submit_form', [$this->submissionController, 'handlePost']);
        add_action('admin_post_nopriv_wjm_submit_form', [$this->submissionController, 'handlePost']);


    }
}
