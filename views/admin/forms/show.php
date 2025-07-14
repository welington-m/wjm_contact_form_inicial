<div class="wrap wjm-form-view">
    <h1 class="wp-heading-inline">Visualizar Formulário: <?php echo esc_html($form->title); ?></h1>
    
    <div class="wjm-editor-actions">
        <a href="<?php echo esc_url(admin_url('admin.php?page=wjm_form_editor&id=' . $form->id)); ?>" class="button">
            <span class="dashicons dashicons-edit"></span> Editar
        </a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wjm_forms')); ?>" class="button">
            <span class="dashicons dashicons-arrow-left-alt"></span> Voltar
        </a>
    </div>
    
    <hr class="wp-header-end">

    <div class="wjm-form-preview">
        <div class="wjm-form-group">
            <h2>Shortcode</h2>
            <div class="shortcode-preview">
                <code>[wjm_form id="<?php echo esc_attr((string)$form->id); ?>"]</code>
                <button class="button button-small wjm-copy-shortcode" data-shortcode='[wjm_form id="<?php echo esc_attr((string)$form->id); ?>"]'>
                    <span class="dashicons dashicons-clipboard"></span> Copiar
                </button>
            </div>
        </div>

        <div class="wjm-form-group">
            <h2>Pré-visualização</h2>
            <div class="wjm-form-preview-container">
                <?php echo do_shortcode('[wjm_form id="' . esc_attr((string)$form->id) . '" preview="true"]'); ?>
            </div>
        </div>

        <div class="wjm-form-group">
            <h2>Configurações</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">E-mail de Destino</th>
                        <td><?php echo $form->recipientEmail ? esc_html($form->recipientEmail) : 'E-mail padrão do WordPress'; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Mensagem de Sucesso</th>
                        <td><?php echo esc_html($form->successMessage); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Mensagem de Erro</th>
                        <td><?php echo esc_html($form->errorMessage); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="wjm-form-group">
            <h2>Campos</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Rótulo</th>
                        <th>Tipo</th>
                        <th>Obrigatório</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($form->fields as $field): ?>
                    <tr>
                        <td><?php echo esc_html($field->name ?? 'campo_' . $field->type); ?></td>
                        <td><?php echo esc_html($field->label); ?></td>
                        <td><?php echo esc_html($field->type); ?></td>
                        <td><?php echo $field->required ? 'Sim' : 'Não'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>