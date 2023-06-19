<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\DB;

class UserRepositoryImplement implements UserRepository
{
    public function getAllUsers(): array
    {
        try {
            return DB::select("SELECT * FROM users");
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getUserById(int $id): array
    {
        try {
            $user = DB::select("SELECT * FROM users WHERE id = ?", [$id]);

            if (empty($user)) {
                return ["error" => "User not found"];
            }

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

            DB::insert("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)", [$name, $email, $password, now(), now()]);

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
                $name = $userById[0]->name;
            }

            if ($email == "") {
                $email = $userById[0]->email;
            }

            if ($password == "") {
                $password = $userById[0]->password;
            }

            DB::update("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?", [$name, $email, $password, $userById[0]->id]);

            return ["message" => sprintf("User ID : '%s' is updated!", $userById[0]->id)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function deleteUserById($id): array
    {
        try {
            DB::delete("DELETE FROM users WHERE id = ?", [$id]);

            return ["message" => sprintf("User ID : %d is deleted!", $id)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
