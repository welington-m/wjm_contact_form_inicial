<?php

namespace WJM\Application\UseCase\Form;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Application\UseCase\Form\DTO\DeleteFormDTO;

final class DeleteFormUseCase
{
    public function __construct(
        private FormRepositoryInterface $formRepository
    ) {}

    public function execute(DeleteFormDTO $dto): bool
    {
        return $this->formRepository->delete($dto->formId);
    }
}