<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all(): object;
    public function find(int $id): object;

    public function findWhereFirst(string $column, mixed $value): object;

    public function findWhereAll(string $column, mixed $value): object;
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
}
