<?php

namespace WJM\Application\UseCase\Form\DTO;

final class UpdateFormDTO
{

    public function __construct(
        public int $id,
        public string $title,
        public array $fields,
        public ?string $recipientEmail = null,
        public ?string $submitButtonText = null,
        public ?string $errorMessage = null,
        public ?string $successMessage = null,
    ){
        if ($id <= 0) {
            throw new \InvalidArgumentException("ID inválido para atualização.");
        }
    }
}