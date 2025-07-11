<?php

namespace WJM\Domain\Entities;

class FormMessage
{
    public function __construct(
        public ?int $id,
        public int $formId,
        public array $data,
        public \DateTimeImmutable $submittedAt,
        public string $ipAddress,
        public bool $viewed = false
    ) {}
}
