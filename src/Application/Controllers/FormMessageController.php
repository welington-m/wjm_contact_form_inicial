<?php
namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormMessageRepositoryInterface;

class FormMessageController
{
    public function __construct(private FormMessageRepositoryInterface $repo) {}

    public function list(array $filters, int $page, int $perPage): array {
        return $this->repo->findAll($filters, $page, $perPage);
    }

    public function total(array $filters): int {
        return $this->repo->count($filters);
    }

    public function export(array $filters): string {
        return $this->repo->export($filters);
    }

    public function markViewed(int $id, string $username): void {
        $this->repo->markAsViewed($id, $username);
    }
}
