<?php
/** @var \WJM\Domain\Entities\Form $form */
if (!$form) {
    echo '<p>Formulário não encontrado.</p>';
    return;
}

$form_status = $_GET['form_status'] ?? '';
$message = $_GET['message'] ?? '';
?>

<div class="wjm-contact-form">
    <?php if (!empty($form_status) && !empty($message)): ?>
        <div class="<?php echo $form_status === 'success' ? 'wjm-success' : 'wjm-errors'; ?>">
            <?php echo esc_html($message); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="wjm_submit_form">
        <input type="hidden" name="form_id" value="<?php echo esc_attr($form->id); ?>">
        <?php wp_nonce_field('wjm_form_submit', 'wjm_nonce'); ?>

        <?php foreach ($form->fields as $field): ?>
            <div class="wjm-form-group">
                <label for="<?php echo esc_attr($field->name); ?>">
                    <?php echo esc_html($field->label); ?>
                    <?php if ($field->required): ?> *<?php endif; ?>
                </label>

                <?php if ($field->type === 'textarea'): ?>
                    <textarea 
                        id="<?php echo esc_attr($field->name); ?>" 
                        name="<?php echo esc_attr($field->name); ?>" 
                        <?php echo $field->required ? 'required' : ''; ?>
                    ></textarea>
                <?php elseif ($field->type === 'select'): ?>
                    <select 
                        id="<?php echo esc_attr($field->name); ?>" 
                        name="<?php echo esc_attr($field->name); ?>" 
                        <?php echo $field->required ? 'required' : ''; ?>
                    >
                        <?php foreach ($field->options as $option): ?>
                            <option value="<?php echo esc_attr($option); ?>">
                                <?php echo esc_html($option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input 
                        type="<?php echo esc_attr($field->type); ?>" 
                        id="<?php echo esc_attr($field->name); ?>" 
                        name="<?php echo esc_attr($field->name); ?>" 
                        <?php echo $field->required ? 'required' : ''; ?>
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit">
            <?php echo esc_html($form->submitButtonText ?? 'Enviar'); ?>
        </button>
    </form>
</div>
