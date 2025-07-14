<?php

namespace WJM\Infra\Repositories;

use WJM\Domain\Repositories\FormMessageRepositoryInterface;
use WJM\Domain\Entities\FormMessage;
use wpdb;

class FormMessageRepository implements FormMessageRepositoryInterface
{
    public function __construct(private wpdb $wpdb) {}

    public function save(FormMessage $message): bool
    {
        $result = $this->wpdb->insert(
            $this->wpdb->prefix . 'wjm_form_messages',
            [
                'form_id' => $message->formId,
                'data' => json_encode($message->data),
                'submitted_at' => $message->submittedAt->format('Y-m-d H:i:s'),
                'ip_address' => $message->ipAddress,
                'viewed' => (int) $message->viewed
            ]
        );

        return $result !== false;
    }

    public function findByFormId(int $formId): array
    {
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}wjm_form_messages WHERE form_id = %d",
                $formId
            ),
            ARRAY_A
        );

        return $results ?: [];
    }
}