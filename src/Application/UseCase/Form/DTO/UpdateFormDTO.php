<?php

namespace WJM\Application\UseCase\Form\DTO;

final class UpdateFormDTO
{
    public readonly int $id;
    public readonly string $title;
    public readonly array $fields;

    public function __construct(int $id, string $title, array $fields)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("ID inválido para atualização.");
        }

        $this->id = $id;
        $this->title = $title;
        $this->fields = $fields;
    }
}
