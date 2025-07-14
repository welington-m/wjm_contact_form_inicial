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

    /**
     * Exibe a lista de formulários
     */
    public function index(): void
    {
        $this->view->render('admin/forms/index', [
            'forms' => $this->listFormsUseCase->execute(),
            'new_form_url' => $this->urlHelper->getNewFormUrl()
        ]);
    }

    /**
     * Exibe o formulário de criação/edição
     */
    public function edit($id = null): void
    {
        $id = is_numeric($id) ? (int) $id : null;
        $form = $id ? $this->getFormUseCase->execute(new GetFormDTO($id)) : null;
        
        $this->view->render('admin/forms/edit', [
            'form' => $form,
            'back_url' => $this->urlHelper->getNewFormUrl()
        ]);
    }

    /**
     * Processa a criação/atualização de um formulário
     */
    public function save(array $request): bool
    {
        if (empty($request['id'])) {
            $dto = new CreateFormDTO(
                title: $request['name'] ?? 'Formulário sem nome',
                fields: $request['fields'] ?? [],
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
                fields: $request['fields'] ?? [],
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
        // 1. Verifica nonce (opcional mas recomendado)
        if (!current_user_can('manage_options')) {
            wp_die(__('Você não tem permissão para isso.', 'wjm'));
        }

        // 2. Sanitiza os dados
        $postData = $_POST;
        $postData['fields'] = json_decode(stripslashes($postData['fields'] ?? '[]'), true);

        // 3. Salva usando a aplicação
        $this->save($postData);

        // 4. Redireciona com sucesso ou erro
        $redirectUrl = admin_url('admin.php?page=wjm_forms');
        wp_redirect($redirectUrl);
        exit;
    }


    /**
     * Remove um formulário
     */
    public function delete(int $formId): bool
    {
        return $this->deleteFormUseCase->execute(new DeleteFormDTO($formId));
    }

    /**
     * Exibe os detalhes de um formulário
     */
    public function show($id): void
    {
        $id = is_numeric($id) ? (int)$id : 0;

        $form = $this->getFormUseCase->execute(new GetFormDTO($id));
        
        $this->view->render('admin/forms/index', [
            'form' => $form,
            'edit_url' => $this->urlHelper->getNewFormUrl() . '&id=' . $id
        ]);
    }
}