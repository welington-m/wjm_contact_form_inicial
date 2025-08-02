<?php

namespace WJM\Domain\Repositories;

use WJM\Domain\Entities\FormMessage;

interface FormMessageExportRepositoryInterface
{
    public function getAllMessages(): array;
    public function getMessageById(int $id): ?FormMessage;
}