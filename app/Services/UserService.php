<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function findFirst(string $column, mixed $value)
    {
        return $this->repository->findFirst($column, $value);
    }
}
