<?php

namespace WJM\Application\Handlers;

use wpdb;
use WJM\Domain\Entities\FormData;
use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Infra\Services\EmailSender;
use WJM\Validators\FormValidator;

class FormSubmissionHandler
{
    private wpdb $db;
    private FormRepositoryInterface $formRepository;
    private EmailSender $emailSender;

    public function __construct(wpdb $db, FormRepositoryInterface $formRepository, EmailSender $emailSender)
    {
        $this->db = $db;
        $this->formRepository = $formRepository;
        $this->emailSender = $emailSender;
    }

    public function handle(int $formId, array $submittedData): array
    {
        $form = $this->formRepository->findById($formId);

        if (!$form) {
            return ['success' => false, 'errors' => ['Formulário não encontrado.']];
        }

        $errors = FormValidator::validate($form->fields, $submittedData);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Salvar no banco de dados (exemplo)
        $this->db->insert("{$this->db->prefix}wjm_form_messages", [
            'form_id' => $formId,
            'data' => json_encode($submittedData),
            'created_at' => current_time('mysql')
        ]);

        // Enviar email (exemplo)
        $this->emailSender->send($form, $submittedData);

        return ['success' => true];
    }
}
