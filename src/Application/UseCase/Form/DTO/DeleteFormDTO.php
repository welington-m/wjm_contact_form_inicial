<?php

namespace WJM\Application\UseCase\Form\DTO;

final class DeleteFormDTO
{
    public function __construct(
        public readonly int $formId
    ) {}
}