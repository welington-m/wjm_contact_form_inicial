<?php

namespace WJM\Application\UseCase\Form\DTO;

final class UpdateFormDTO
{

    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly array $fields,
        public readonly ?string $recipientEmail,
        public readonly ?string $submitButtonText,
        public readonly ?string $errorMessage,
        public readonly ?string $successMessage,
    ){}
}