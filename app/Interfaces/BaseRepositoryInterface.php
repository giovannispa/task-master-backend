<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all(): array;
    public function find(int $id): object;

    public function findWhereFirst(string $column, mixed $value): object;

    public function findWhereAll(string $column, mixed $value): object;

    public function create(array $data): object;

    public function update(int $id, array $data): object;

    public function delete(int $id): bool;

    public function attach(string $relation, int $primary_id, int $foreign_id): ?object;

    public function detach(string $relation, int $primary_id, int $foreign_id): ?object;
}
