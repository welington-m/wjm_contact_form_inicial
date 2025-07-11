<?php

namespace WJM\Application\UseCase\Form;

use WJM\Application\UseCase\Form\DTO\SubmissionDTO;
use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Domain\Factories\FormMessageFactory;
use WJM\Infra\Services\EmailSender;

final class SubmitFormUseCase
{
    public function __construct(
        private FormRepositoryInterface $formRepository,
        private EmailSender $emailSender,
        private FormMessageFactory $messageFactory
    ) {}

    public function execute(SubmissionDTO $dto): bool
    {
        $form = $this->formRepository->findById($dto->form_id);

        if (!$form) {
            return false;
        }

        $message = $this->messageFactory->fromDTO($dto, $form);
        $this->formRepository->storeSubmission($message);

        $this->emailSender->send(
            to: $form->recipientEmail,
            subject: "Nova mensagem do formulário: {$form->title}",
            body: $message->toHtml()
        );

        return true;
    }
}
