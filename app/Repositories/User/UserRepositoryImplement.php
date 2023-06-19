<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

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
}
