<?php

namespace App\DTO\Response\User;

class UserResponse
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(array $user)
    {
        $this->id = $user["id"] ?? 0;
        $this->name = $user["name"] ?? "";
        $this->email = $user["email"] ?? "";
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
