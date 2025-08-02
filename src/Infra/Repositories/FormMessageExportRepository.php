<?php

namespace WJM\Infra\Repositories;

use WJM\Domain\Entities\FormMessage;
use WJM\Domain\Repositories\FormMessageExportRepositoryInterface;
use wpdb;

class FormMessageExportRepository implements FormMessageExportRepositoryInterface
{
    public function __construct(private wpdb $wpdb) {}

    public function getAllMessages(): array
    {
        $results = $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}wjm_form_messages ORDER BY submitted_at DESC", ARRAY_A);

        return array_map(function ($row) {
            return new FormMessage(
                id: (int)$row['id'],
                formId: (int)$row['form_id'],
                data: json_decode($row['data'], true) ?: [],
                submittedAt: new \DateTimeImmutable($row['submitted_at']),
                ipAddress: $row['ip_address'] ?? '',
                viewed: (bool)($row['viewed'] ?? false)
            );
        }, $results);
    }

    public function getMessageById(int $id): ?FormMessage
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}wjm_form_messages WHERE id = %d", $id),
            ARRAY_A
        );

        if (!$row) return null;

        return new FormMessage(
            id: (int)$row['id'],
            formId: (int)$row['form_id'],
            data: json_decode($row['data'], true) ?: [],
            submittedAt: new \DateTimeImmutable($row['submitted_at']),
            ipAddress: $row['ip_address'] ?? '',
            viewed: (bool)($row['viewed'] ?? false)
        );
    }
}
