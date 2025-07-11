<?php

namespace WJM\Infra\Wordpress\Shortcodes;

use WJM\Infra\Repositories\FormRepository;
use WJM\Infra\Services\EmailSender;
use WJM\Application\Controllers\SubmissionController;
use WJM\Infra\WordPress\Form\FormRenderer;

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

        $form = $repo->findById($formId);
        ob_start();
        echo FormRenderer::render($form);

        if ($result) {
            echo '<div style="color: ' . ($result['success'] ? 'green' : 'red') . ';">';
            echo $result['success']
                ? $result['message']
                : implode('<br>', $result['errors'] ?? [$result['error']]);
            echo '</div>';
        }

        return ob_get_clean();
    }
}
