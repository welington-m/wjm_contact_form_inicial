<?php

namespace WJM\Infra\Services;

use WJM\Domain\Entities\Form;

class EmailSender
{
    public function send(Form $form, array $data): bool
    {
        $to = $form->recipient ?? get_option('admin_email');
        $subject = "Nova mensagem do formulário: " . $form->name;

        // Caminho absoluto ao template externo
        $template_path = plugin_dir_path(__FILE__) . '../../../views/email/contact-message-template.html';

        if (!file_exists($template_path)) {
            return false;
        }

        $template = file_get_contents($template_path);
        $template = str_replace("{{form_name}}", $form->name, $template);

        $fieldsHtml = "";
        foreach ($data as $key => $value) {
            $fieldsHtml .= "<li><strong>" . ucfirst($key) . "</strong>: " . nl2br(htmlspecialchars($value)) . "</li>";
        }

        $template = str_replace("{{fields}}", $fieldsHtml, $template);

        $headers = ['Content-Type: text/html; charset=UTF-8'];

        return wp_mail($to, $subject, $template, $headers);
    }
}
