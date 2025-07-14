<?php

namespace WJM\Domain\Repositories;

use WJM\Domain\Entities\FormMessage;

interface FormMessageRepositoryInterface
{
    public function save(FormMessage $message): bool;
    public function findByFormId(int $formId): array;
}