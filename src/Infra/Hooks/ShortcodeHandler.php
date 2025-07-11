<?php

namespace WJM\Infra\Hooks;

use WJM\Infra\Repositories\FormRepository;
use WJM\Infra\Services\EmailSender;
use WJM\Application\Controllers\SubmissionController;

class ShortcodeHandler
{
    public static function register()
    {
        add_shortcode('contact-form', [self::class, 'render']);
    }

    public static function render($atts)
    {
        $atts = shortcode_atts(['id' => 0], $atts, 'contact-form');
        $formId = (int) $atts['id'];

        global $wpdb;
        $repo = new FormRepository($wpdb);
        $emailSender = new EmailSender();
        $controller = new SubmissionController($repo, $emailSender, $wpdb);

        $result = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wjm_form_id']) && $_POST['wjm_form_id'] == $formId) {
            $result = $controller->handle($formId, $_POST);
        }

        ob_start();
        ?>
        <form method="post">
            <input type="hidden" name="wjm_form_id" value="<?= esc_attr($formId) ?>">
            <label>Nome: <input type="text" name="name" required></label><br>
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Mensagem: <textarea name="message" required></textarea></label><br>
            <button type="submit">Enviar</button>
        </form>
        <?php if ($result): ?>
            <div style="color: <?= $result['success'] ? 'green' : 'red' ?>;">
                <?= $result['success'] ? $result['message'] : implode('<br>', $result['errors'] ?? [$result['error']]) ?>
            </div>
        <?php endif;
        return ob_get_clean();
    }
}
