<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all();
    public function find(int $id);

    public function findFirst(string $column, mixed $value);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
