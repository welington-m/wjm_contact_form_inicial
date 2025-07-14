<?php

namespace WJM\Application\Controllers;

use WJM\Application\UseCase\Form\SubmitFormUseCase;
use WJM\Application\UseCase\Form\DTO\SubmissionDTO;
use WJM\Validators\FormValidator;
use WJM\Infra\WordPress\View;

class SubmissionController
{
    public function __construct(
        private SubmitFormUseCase $submitUseCase,
        private View $view
    ) {}

    public function handle(int $formId, array $data): array
    {
        $dto = new SubmissionDTO($formId, $data);
        return $this->submitUseCase->execute($dto);
    }

    public function renderShortcode($atts): string
    {
        // Implementação do shortcode
        $formId = $atts['id'] ?? 0;
        return $this->view->render('public/form', ['formId' => $formId]);
    }
}