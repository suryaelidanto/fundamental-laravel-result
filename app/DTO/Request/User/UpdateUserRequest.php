<?php

namespace App\DTO\Request\User;

use Illuminate\Support\Facades\Validator;

class UpdateUserRequest
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(array $user)
    {
        $this->name = $user["name"] ?? "";
        $this->email = $user["email"] ?? "";
        $this->password = $user["password"] ?? "";
    }

    public function validate(): array
    {
        $validator = Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ], [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', "unique:users,email"],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return [
                'error' => $errors,
            ];
        }

        return [];
    }
}
