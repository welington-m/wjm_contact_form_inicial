<?php

namespace WJM\Application\UseCase\Form;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Domain\Entities\Form;
use WJM\Application\UseCase\Form\DTO\GetFormDTO;

final class GetFormUseCase
{
    public function __construct(
        private FormRepositoryInterface $formRepository
    ) {}

    public function execute(GetFormDTO $dto): ?Form
    {
        return $this->formRepository->findById($dto->formId);
    }
}