<?php

namespace App\Repositories\User;

interface UserRepository
{
    public function getAllUsers(): array;
    public function getUserById(int $id): array;
}
