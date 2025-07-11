<?php

namespace WJM\Infra\Repositories;

use WJM\Domain\Entities\Form;
use WJM\Domain\Entities\FormField;
use WJM\Domain\Entities\FormMessage;
use WJM\Domain\Repositories\FormRepositoryInterface;
use wpdb;

class FormRepository implements FormRepositoryInterface
{
    public function __construct(private wpdb $db) {}

    public function findById(int $id): ?Form
    {
        $row = $this->db->get_row($this->db->prepare("SELECT * FROM wjm_forms WHERE id = %d", $id), ARRAY_A);

        if (!$row) return null;

        $fields = json_decode($row['fields'], true) ?? [];
        $formFields = array_map(fn($f) => new FormField(...$f), $fields);

        return new Form(
            id: (int) $row['id'],
            title: $row['title'],
            fields: $formFields,
            recipientEmail: $row['recipient_email'],
            submitButtonText: $row['submit_button_text'],
            errorMessage: $row['error_message'],
            successMessage: $row['success_message']
        );
    }

    public function findAll(): array
    {
        $rows = $this->db->get_results("SELECT * FROM wjm_forms", ARRAY_A);

        return array_map(function ($row) {
            $fields = json_decode($row['fields'], true) ?? [];
            $formFields = array_map(fn($f) => new FormField(...$f), $fields);

            return new Form(
                id: (int) $row['id'],
                title: $row['title'],
                fields: $formFields,
                recipientEmail: $row['recipient_email'],
                submitButtonText: $row['submit_button_text'],
                errorMessage: $row['error_message'],
                successMessage: $row['success_message']
            );
        }, $rows);
    }

    public function save(Form $form): int
    {
        $this->db->insert('wjm_forms', [
            'title' => $form->title,
            'fields' => json_encode($form->fields),
            'recipient_email' => $form->recipientEmail,
            'submit_button_text' => $form->submitButtonText,
            'error_message' => $form->errorMessage,
            'success_message' => $form->successMessage
        ]);

        return (int) $this->db->insert_id;
    }

    public function update(Form $form): void
    {
        $this->db->update('wjm_forms', [
            'title' => $form->title,
            'fields' => json_encode($form->fields),
            'recipient_email' => $form->recipientEmail,
            'submit_button_text' => $form->submitButtonText,
            'error_message' => $form->errorMessage,
            'success_message' => $form->successMessage
        ], ['id' => $form->id]);
    }

    public function delete(int $formId): void
    {
        $this->db->delete('wjm_forms', ['id' => $formId]);
    }

    public function storeSubmission(FormMessage $message): void
    {
        $this->db->insert('wjm_form_messages', [
            'form_id' => $message->formId,
            'data' => json_encode($message->data),
            'submitted_at' => $message->submittedAt->format('Y-m-d H:i:s'),
            'ip_address' => $message->ipAddress,
            'viewed' => (int) $message->viewed
        ]);
    }
}
