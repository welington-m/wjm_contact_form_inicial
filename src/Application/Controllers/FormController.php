<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Domain\Entities\Form;
use WJM\Domain\Entities\FormField;

class FormController
{
    private FormRepositoryInterface $formRepository;

    public function __construct(FormRepositoryInterface $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function save(array $request): bool
    {
        $form = new Form();
        $form->id = $request['id'] ?? 0;
        $form->name = $request['name'] ?? 'Formulário sem nome';
        $form->recipient = $request['recipient'] ?? null;
        $form->fields = [];

        if (!empty($request['fields']) && is_array($request['fields'])) {
            foreach ($request['fields'] as $fieldData) {
                $field = new FormField();
                $field->label = $fieldData['label'] ?? '';
                $field->type = $fieldData['type'] ?? 'text';
                $field->required = !empty($fieldData['required']);
                $field->options = $fieldData['options'] ?? [];

                $form->fields[] = $field;
            }
        }

        $form->createdAt = current_time('mysql');
        $form->updatedAt = current_time('mysql');

        $this->formRepository->save($form);
        return true;
    }

    public function delete(int $formId): bool
    {
        return $this->formRepository->delete($formId);
    }
}
