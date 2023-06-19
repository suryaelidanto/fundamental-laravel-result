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
            $user = DB::selectOne("SELECT * FROM users WHERE id = ?", [$id]);

            if (empty($user)) {
                return ["error" => "User not found"];
            }

            return get_object_vars($user);
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
