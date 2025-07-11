<?php
namespace WJM\Domain\Repositories;

use WJM\Domain\Entities\FormMessage;

interface FormMessageRepositoryInterface {
    public function findAll(array $filters = [], int $page = 1, int $perPage = 10): array;
    public function count(array $filters = []): int;
    public function export(array $filters = []): string;
    public function markAsViewed(int $id, string $username): void;
}
