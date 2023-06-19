<?php

namespace App\DTO\Request\User;

use Illuminate\Support\Facades\Validator;

class CreateUserRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
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
