<?php

namespace WJM\Infra\Hooks;

use WJM\Infra\WordPress\AdminMenu;
use WJM\Infra\WordPress\AssetsLoader;

class HookRegistrar
{
    private AdminMenu $adminMenu;
    private AssetsLoader $assetsLoader;

    public function __construct(
        AdminMenu $adminMenu,
        AssetsLoader $assetsLoader
    ) {
        $this->adminMenu = $adminMenu;
        $this->assetsLoader = $assetsLoader;
    }

    public function register(): void
    {
        $this->adminMenu->register();
        $this->assetsLoader->register();

        // Aqui você pode adicionar outros hooks globais
        // add_action('init', [$this, 'algumaFuncaoGlobal']);
    }
}
