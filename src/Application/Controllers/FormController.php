<?php

namespace WJM\Application\Controllers;

use WJM\Application\UseCase\Form\CreateFormUseCase;
use WJM\Application\UseCase\Form\UpdateFormUseCase;
use WJM\Application\UseCase\Form\GetFormUseCase;
use WJM\Application\UseCase\Form\DeleteFormUseCase;
use WJM\Application\UseCase\Form\ListFormsUseCase;
use WJM\Application\UseCase\Form\DTO\CreateFormDTO;
use WJM\Application\UseCase\Form\DTO\UpdateFormDTO;
use WJM\Application\UseCase\Form\DTO\GetFormDTO;
use WJM\Application\UseCase\Form\DTO\DeleteFormDTO;
use WJM\Infra\WordPress\Helpers\UrlHelper;
use WJM\Infra\WordPress\View;

class FormController
{
    public function __construct(
        private CreateFormUseCase $createFormUseCase,
        private UpdateFormUseCase $updateFormUseCase,
        private GetFormUseCase $getFormUseCase,
        private DeleteFormUseCase $deleteFormUseCase,
        private ListFormsUseCase $listFormsUseCase,
        private View $view,
        private UrlHelper $urlHelper
    ) {}

    public function index(): void
    {
        $this->view->render('admin/forms/index', [
            'forms' => $this->listFormsUseCase->execute(),
            'new_form_url' => $this->urlHelper->getNewFormUrl()
        ]);
    }

    public function edit($id = null): void
    {
        if ($id === null && isset($_GET['id'])) {
            $id = (int) $_GET['id'];
        }

        $form = null;
        if ($id) {
            $form = $this->getFormUseCase->execute(new GetFormDTO($id));
        }

        $this->view->render('admin/forms/edit', [
            'form' => $form,
            'back_url' => $this->urlHelper->getNewFormUrl()
        ]);
    }

    public function save(array $request): bool
    {
        // Garante compatibilidade com a estrutura que vem da view
        $fields = $request['fields']['fields'] ?? $request['fields'] ?? [];

        if (empty($request['id'])) {
            $dto = new CreateFormDTO(
                title: $request['name'] ?? 'Formulário sem nome',
                fields: $fields,
                recipientEmail: $request['recipient'] ?? null,
                submitButtonText: $request['submit_button_text'] ?? 'Enviar',
                errorMessage: $request['error_message'] ?? '',
                successMessage: $request['success_message'] ?? ''
            );

            $this->createFormUseCase->execute($dto);
        } else {
            $dto = new UpdateFormDTO(
                id: (int) $request['id'],
                title: $request['name'] ?? 'Formulário sem nome',
                fields: $fields,
                recipientEmail: $request['recipient'] ?? null,
                submitButtonText: $request['submit_button_text'] ?? 'Enviar',
                errorMessage: $request['error_message'] ?? '',
                successMessage: $request['success_message'] ?? ''
            );

            $this->updateFormUseCase->execute($dto);
        }

        return true;
    }

    public function handleSave(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('Você não tem permissão para isso.', 'wjm'));
        }

        $postData = $_POST;
        $postData['fields'] = json_decode(stripslashes($postData['fields'] ?? '[]'), true);

        $this->save($postData);

        $redirectUrl = admin_url('admin.php?page=wjm_forms');
        wp_redirect($redirectUrl);
        exit;
    }

    public function delete(int $formId): bool
    {
        return $this->deleteFormUseCase->execute(new DeleteFormDTO($formId));
    }

    public function show($id): void
    {
        $id = is_numeric($id) ? (int) $id : 0;

        $form = $this->getFormUseCase->execute(new GetFormDTO($id));

        $this->view->render('admin/forms/index', [
            'form' => $form,
            'edit_url' => $this->urlHelper->getNewFormUrl() . '&id=' . $id
        ]);
    }
}
