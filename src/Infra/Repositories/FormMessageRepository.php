<?php

namespace WJM\Infra\Repositories;

use wpdb;
use WJM\Domain\Entities\FormMessage;
use WJM\Domain\Repositories\FormMessageRepositoryInterface;

class FormMessageRepository implements FormMessageRepositoryInterface
{
    public function __construct(private wpdb $wpdb) {}

    public function save(FormMessage $message): bool
    {
        $data = [
            'form_id' => $message->formId,
            'data' => json_encode($message->data),
            'submitted_at' => $message->submittedAt->format('Y-m-d H:i:s'),
            'ip_address' => $message->ipAddress,
            'viewed' => (int) $message->viewed
        ];

        return (bool) $this->wpdb->insert(
            $this->wpdb->prefix . 'wjm_form_messages',
            $data
        );
    }

    public function findById(int $id): ?FormMessage
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}wjm_form_messages 
                WHERE id = %d",
                $id
            )
        );

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    public function findByFormId(int $formId): array
    {
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}wjm_form_messages 
                 WHERE form_id = %d 
                 ORDER BY submitted_at DESC",
                $formId
            )
        );

        return array_map([$this, 'mapToEntity'], $results);
    }

    public function getMessages(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM {$this->wpdb->prefix}wjm_form_messages";
        $params = [];

        if ($search !== '') {
            $query .= " WHERE data LIKE %s";
            $params[] = '%' . $this->wpdb->esc_like($search) . '%';
        }

        $query .= " ORDER BY submitted_at DESC LIMIT %d OFFSET %d";
        $params[] = $perPage;
        $params[] = $offset;

        $results = $this->wpdb->get_results(
            $this->wpdb->prepare($query, ...$params)
        );

        return array_map([$this, 'mapToEntity'], $results);
    }

    public function countMessages(string $search = ''): int
    {
        $query = "SELECT COUNT(*) FROM {$this->wpdb->prefix}wjm_form_messages";
        
        if ($search !== '') {
            $query .= " WHERE data LIKE %s";
            return (int) $this->wpdb->get_var(
                $this->wpdb->prepare($query, '%' . $this->wpdb->esc_like($search) . '%')
            );
        }

        return (int) $this->wpdb->get_var($query);
    }

    public function markAsViewed(int $id, string $username): bool
    {
        return (bool) $this->wpdb->update(
            $this->wpdb->prefix . 'wjm_form_messages',
            [
                'viewed' => 1,
                'viewed_by' => $username,
                'viewed_at' => current_time('mysql')
            ],
            ['id' => $id],
            ['%d', '%s', '%s'],
            ['%d']
        );
    }

    public function export(array $filters): string
    {
        // Implementação de exportação para CSV/Excel
        // [...] (código de exportação)
    }

    private function mapToEntity(object $row): FormMessage
    {
        return new FormMessage(
            id: (int)$row->id,
            formId: (int)$row->form_id,
            data: json_decode($row->data, true) ?: [],
            submittedAt: new \DateTimeImmutable($row->submitted_at),
            ipAddress: $row->ip_address,
            viewed: (bool)$row->viewed
        );
    }
}