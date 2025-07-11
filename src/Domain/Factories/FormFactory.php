<?php

namespace WJM\Domain\Factories;

use WJM\Application\UseCase\Form\DTO\CreateFormDTO;
use WJM\Application\UseCase\Form\DTO\UpdateFormDTO;
use WJM\Domain\Entities\Form;
use WJM\Domain\Entities\FormField;

class FormFactory
{
    /**
     * Cria uma entidade Form a partir de um DTO de criação
     */
    public static function fromCreateDTO(CreateFormDTO $dto): Form
    {
        return new Form(
            id: null,
            title: $dto->title,
            fields: self::mapFields($dto->fields),
            recipientEmail: $dto->recipient_email,
            submitButtonText: $dto->submit_button_text,
            errorMessage: $dto->error_message,
            successMessage: $dto->success_message
        );
    }

    /**
     * Cria uma entidade Form a partir de um DTO de atualização
     */
    public static function fromUpdateDTO(UpdateFormDTO $dto, int $id): Form
    {
        return new Form(
            id: $id,
            title: $dto->title,
            fields: self::mapFields($dto->fields),
            recipientEmail: $dto->recipient_email,
            submitButtonText: $dto->submit_button_text,
            errorMessage: $dto->error_message,
            successMessage: $dto->success_message
        );
    }

    /**
     * Mapeia campos do DTO para entidades FormField
     */
    private static function mapFields(array $fields): array
    {
        return array_map(
            fn($field) => new FormField(
                name: $field['name'] ?? '',
                type: $field['type'] ?? 'text',
                label: $field['label'] ?? '',
                required: $field['required'] ?? false
            ),
            $fields
        );
    }
}
