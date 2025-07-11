<?php
namespace WJM\Infra\Repositories;

use wpdb;
use WJM\Domain\Entities\FormMessage;
use WJM\Domain\Repositories\FormMessageRepositoryInterface;

class FormMessageRepository implements FormMessageRepositoryInterface
{
    private wpdb $db;
    private string $table;

    public function __construct(wpdb $db) {
        $this->db = $db;
        $this->table = $db->prefix . 'wjm_form_messages';
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $args = [];

        if (!empty($filters['form_id'])) {
            $sql .= " AND form_id = %d";
            $args[] = $filters['form_id'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(created_at) = %s";
            $args[] = $filters['date'];
        }

        $sql .= " ORDER BY created_at DESC LIMIT %d OFFSET %d";
        $args[] = $perPage;
        $args[] = $offset;

        return $this->db->get_results($this->db->prepare($sql, ...$args));
    }

    public function count(array $filters = []): int {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $args = [];

        if (!empty($filters['form_id'])) {
            $sql .= " AND form_id = %d";
            $args[] = $filters['form_id'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(created_at) = %s";
            $args[] = $filters['date'];
        }

        return (int) $this->db->get_var($this->db->prepare($sql, ...$args));
    }

    public function export(array $filters = []): string {
        $messages = $this->findAll($filters, 1, 9999); // exporta tudo
        $lines = ["ID,Formulário,Data,Conteúdo"];

        foreach ($messages as $msg) {
            $lines[] = "{$msg->id},{$msg->form_id},{$msg->created_at},\"" . str_replace('"', '""', $msg->data) . "\"";
        }

        return implode(PHP_EOL, $lines);
    }

    public function markAsViewed(int $id, string $username): void {
        $this->db->update($this->table, [
            'viewed_by' => $username,
            'viewed_at' => current_time('mysql')
        ], ['id' => $id]);
    }
}
