<?php

namespace WJM\Infra\Services;

use WJM\Domain\Entities\Form;
use WJM\Infra\Services\EmailSenderInterface;

class EmailSender implements EmailSenderInterface
{
    public function send(string $to, string $subject, string $body): bool
    {
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        return wp_mail($to, $subject, $body, $headers);
    }
}