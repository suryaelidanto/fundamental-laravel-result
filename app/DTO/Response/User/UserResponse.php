<?php

namespace App\DTO\Response\User;

use App\Models\User;

class UserResponse
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
