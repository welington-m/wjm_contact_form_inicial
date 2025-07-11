<?php
namespace WJM\Domain\Repositories;

use WJM\Domain\Entities\Form;

interface FormRepositoryInterface {
    public function findById(int $id): ?Form;
    public function save(Form $form): int;
    public function findAll(): array;
    public function create(array $form): int;
    public function update(int $id, array $form): bool;
    public function delete(int $id): bool;
}
