<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Infra\WordPress\View;
use WJM\Infra\WordPress\UrlHelper;

class DashboardController
{
    private FormRepositoryInterface $formRepository;
    private View $view;
    private UrlHelper $urlHelper;

    public function __construct(
        FormRepositoryInterface $formRepository,
        View $view,
        UrlHelper $urlHelper
    ) {
        $this->formRepository = $formRepository;
        $this->view = $view;
        $this->urlHelper = $urlHelper;
    }

    public function show(): void
    {
        $this->view->render('admin/dashboard', [
            'forms' => $this->formRepository->findAll(),
            'new_form_url' => $this->urlHelper->getNewFormUrl(),
            'messages_url' => $this->urlHelper->getMessagesUrl(),
            'banner_url' => $this->urlHelper->getBannerUrl()
        ]);
    }
}
