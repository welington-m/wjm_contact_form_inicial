<?php

namespace WJM\Application\UseCase\Form;

use WJM\Application\UseCase\Form\DTO\UpdateFormDTO;
use WJM\Domain\Factories\FormFactory;
use WJM\Domain\Repositories\FormRepositoryInterface;

final class UpdateFormUseCase
{
    private FormRepositoryInterface $formRepository;

    public function __construct(FormRepositoryInterface $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function execute(int $id, UpdateFormDTO $dto): void
    {
        $form = FormFactory::fromUpdateDTO($dto, $id);
        $this->formRepository->update($form, $id);
    }
}
