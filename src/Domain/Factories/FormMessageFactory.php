<?php

namespace WJM\Domain\Factories;

use WJM\Application\UseCase\Form\DTO\SubmissionDTO;
use WJM\Domain\Entities\Form;
use WJM\Domain\Entities\FormMessage;

final class FormMessageFactory
{
    public function fromDTO(SubmissionDTO $dto, Form $form): FormMessage
    {
        return new FormMessage(
            formId: $dto->formId,
            data: $dto->data,
            ipAddress: $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        );
    }
}
