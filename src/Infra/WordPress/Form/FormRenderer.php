<?php

namespace WJM\Infra\WordPress\Form;

use WJM\Domain\Entities\Form;

class FormRenderer
{
    public static function render(Form $form): string
    {
        ob_start();
        ?>
        <form method="post">
            <input type="hidden" name="wjm_form_id" value="<?= esc_attr($form->id) ?>">

            <?php foreach ($form->fields as $field): ?>
                <div style="margin-bottom:10px;">
                    <label>
                        <?= esc_html($field['label']) ?>:
                        <?php switch ($field['type']):
                            case 'textarea': ?>
                                <textarea name="<?= esc_attr($field['label']) ?>" <?= !empty($field['required']) ? 'required' : '' ?>></textarea>
                            <?php break;
                            case 'select': ?>
                                <select name="<?= esc_attr($field['label']) ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                                        <option value="<?= esc_attr($opt) ?>"><?= esc_html($opt) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php break;
                            case 'radio':
                                foreach ($field['options'] ?? [] as $opt): ?>
                                    <label><input type="radio" name="<?= esc_attr($field['label']) ?>" value="<?= esc_attr($opt) ?>" <?= !empty($field['required']) ? 'required' : '' ?>> <?= esc_html($opt) ?></label>
                                <?php endforeach;
                                break;
                            case 'checkbox':
                                foreach ($field['options'] ?? [] as $opt): ?>
                                    <label><input type="checkbox" name="<?= esc_attr($field['label']) ?>[]" value="<?= esc_attr($opt) ?>"> <?= esc_html($opt) ?></label>
                                <?php endforeach;
                                break;
                            default: ?>
                                <input type="<?= esc_attr($field['type']) ?>" name="<?= esc_attr($field['label']) ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                        <?php endswitch; ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <button type="submit">Enviar</button>
        </form>
        <?php
        return ob_get_clean();
    }
}
