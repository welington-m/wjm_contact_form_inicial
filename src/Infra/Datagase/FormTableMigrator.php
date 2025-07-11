<?php

namespace WJM\Infra\Database;

use wpdb;

class FormTableMigrator
{
    public static function migrate(wpdb $db): void
    {
        $charset_collate = $db->get_charset_collate();
        $table_name = $db->prefix . 'wjm_form_messages';

        $sql = "
        CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            form_id BIGINT UNSIGNED NOT NULL,
            data LONGTEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX (form_id)
        ) {$charset_collate};
        ";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
    
}
