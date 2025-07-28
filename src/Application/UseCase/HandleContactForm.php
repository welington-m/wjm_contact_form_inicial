<?php
namespace WJM\Application\UseCase;

use WJM\Domain\Repositories\FormRepositoryInterface;
use WJM\Domain\Entities\FormData;

class HandleContactForm {
    public function __construct(
        private FormRepositoryInterface $formRepository
    ) {}

    public function execute(int $formId, array $submittedData): bool {
        $form = $this->formRepository->findById($formId);
        if (!$form) return false;
        
        return true;
    }
}
