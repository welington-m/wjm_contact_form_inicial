<?php

namespace WJM\Application\UseCase\Form\DTO;

final class CreateFormDTO
{

    public function __construct(
            public string $title,
            public array   $fields,
            public ?string $recipientEmail,
            public ?string $submitButtonText,
            public ?string $errorMessage,
            public ?string $successMessage
    ){}
}
