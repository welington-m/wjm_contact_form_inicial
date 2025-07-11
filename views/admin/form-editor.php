<?php
declare(strict_types=1);

use function esc_attr_e;
use function esc_html_e;
?>

<div class="wrap wjm-form-editor-container">
    <h1><?php esc_html_e('Editor de Formulário', 'wjm-contact-form'); ?></h1>

    <!-- Botão para mostrar/esconder o editor visual -->
    <button id="wjm-toggle-editor" class="button button-primary">
        <span class="dashicons dashicons-editor-code"></span>
        <span class="btn-text"><?php esc_html_e('Mostrar Editor Visual', 'wjm-contact-form'); ?></span>
    </button>

    <!-- Editor Visual -->
    <div id="wjm-visual-editor" class="wjm-form-group">

        <h2><?php esc_html_e('Campos do Formulário', 'wjm-contact-form'); ?></h2>

        <div id="wjm-fields-container" class="wjm-fields-container">
            <!-- Campos são adicionados dinamicamente via JS -->
        </div>

        <div class="wjm-editor-actions">
            <button id="wjm-add-field" class="button button-secondary">
                <span class="dashicons dashicons-plus-alt"></span>
                <?php esc_html_e('Adicionar Campo', 'wjm-contact-form'); ?>
            </button>

            <button id="wjm-update-json" class="button button-primary">
                <span class="dashicons dashicons-update-alt"></span>
                <?php esc_html_e('Atualizar JSON', 'wjm-contact-form'); ?>
            </button>
        </div>

    </div>

    <!-- Editor de JSON (fallback) -->
    <div class="wjm-form-group">
        <h2><?php esc_html_e('Editor JSON', 'wjm-contact-form'); ?></h2>
        <div class="wjm-json-editor">
            <textarea id="wjm_config_json" name="wjm_config_json" rows="10" class="large-text code">{}</textarea>
        </div>
    </div>

</div>
