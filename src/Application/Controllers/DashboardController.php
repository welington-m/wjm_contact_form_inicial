<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Infra\WordPress\View;

class DashboardController
{
    private FormRepositoryInterface $formRepository;
    private View $view;
    private string $bannerUrl;

    public function __construct(
        FormRepositoryInterface $formRepository,
        View $view,
        string $bannerUrl = ''
    ) {
        $this->formRepository = $formRepository;
        $this->view = $view;
        $this->bannerUrl = $bannerUrl ?: plugins_url('assets/img/banner-470x152.png', WJM_PLUGIN_FILE);
    }

    public function show(): void
    {
        $this->view->render('admin/dashboard', [
            'forms' => $this->formRepository->findAll(),
            'new_form_url' => admin_url('admin.php?page=wjm_form_editor'),
            'messages_url' => admin_url('admin.php?page=wjm_form_messages'),
            'banner_url' => $this->bannerUrl
        ]);
    }
}
