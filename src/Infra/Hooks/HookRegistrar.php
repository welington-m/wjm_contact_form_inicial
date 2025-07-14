<?php

namespace WJM\Infra\Hooks;

use WJM\Application\Controllers\FormController;
use WJM\Infra\WordPress\AdminMenu;
use WJM\Infra\WordPress\AssetsLoader;

class HookRegistrar
{
    private AdminMenu $adminMenu;
    private AssetsLoader $assetsLoader;
    private FormController $formController;

    public function __construct(
        AdminMenu $adminMenu,
        AssetsLoader $assetsLoader,
        FormController $formController
    ) {
        $this->adminMenu = $adminMenu;
        $this->assetsLoader = $assetsLoader;
        $this->formController = $formController;
    }

    public function register(): void
    {
        $this->adminMenu->register();
        $this->assetsLoader->register();

        // Aqui você pode adicionar outros hooks globais
        // add_action('init', [$this, 'algumaFuncaoGlobal']);
        add_action('admin_post_wjm_save_form', [$this->formController, 'handleSave']);
    }
}
