<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Infra\Services\EmailSender;
use WJM\Validators\FormValidator;
use wpdb;

class SubmissionController
{
    private FormRepositoryInterface $formRepository;
    private EmailSender $emailSender;
    private wpdb $db;

    public function __construct(
        FormRepositoryInterface $formRepository,
        EmailSender $emailSender,
        wpdb $db
    ) {
        $this->formRepository = $formRepository;
        $this->emailSender = $emailSender;
        $this->db = $db;
    }

    public function handle(int $formId, array $data): array
    {
        $form = $this->formRepository->findById($formId);
        if (!$form) {
            return ['success' => false, 'error' => 'Formulário não encontrado.'];
        }

        $errors = FormValidator::validate($form->fields, $data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Armazena no banco
        $this->db->insert($this->db->prefix . 'wjm_form_messages', [
            'form_id' => $formId,
            'data' => json_encode($data),
            'created_at' => current_time('mysql')
        ]);

        // Envia email
        $this->emailSender->send($form, $data);

        return ['success' => true, 'message' => 'Mensagem enviada com sucesso.'];
    }
}
