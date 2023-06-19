<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepositoryImplement implements UserRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAllUsers(): array
    {
        try {
            return $this->model->all()->toArray();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getUserById(int $id): array
    {
        try {
            $user = $this->model->find($id);

            if (empty($user)) {
                return ["error" => "User not found"];
            }

            $user = $user->toArray();

            return $user;
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function createUser(array $request): array
    {
        try {
            $name = $request["name"];
            $email = $request["email"];
            $password = $request["password"];

            $this->model->create([
                "name" => $name,
                "email" => $email,
                "password" => $password
            ]);

            return ["message" => sprintf("User email : '%s' is created!", $email)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function updateUserById(array $request, array $userById): array
    {
        try {
            $name = $request["name"] ?? "";
            $email = $request["email"] ?? "";
            $password = $request["password"] ?? "";

            if ($name == "") {
                $name = $userById["name"];
            }

            if ($email == "") {
                $email = $userById["email"];
            }

            if ($password == "") {
                $password = $userById["password"];
            }

            var_dump($userById["id"]);

            $this->model->where("id", $userById["id"])->update([
                "name" => $name,
                "email" => $email,
                "password" => $password
            ]);

            return ["message" => sprintf("User ID : '%s' is updated!", $userById["id"])];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function deleteUserById(int $id): array
    {
        try {
            $this->model->where("id", $id)->delete();

            return ["message" => sprintf("User ID : %d is deleted!", $id)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
