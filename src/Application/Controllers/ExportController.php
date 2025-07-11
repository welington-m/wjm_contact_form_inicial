<?php

namespace WJM\Application\Controllers;

use WJM\Domain\Repositories\FormMessageRepositoryInterface;

class ExportController
{
    private FormMessageRepositoryInterface $repository;

    public function __construct(FormMessageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exportCSV(array $filters = []): void
    {
        $csv = $this->repository->export($filters);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=mensagens_exportadas.csv');

        echo $csv;
        exit;
    }
}
