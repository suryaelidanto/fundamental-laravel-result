# Delete Query with Query Builder

### Repositories

-   Write this code in `UserRepository.php` :

    > File: `app/Repositories/User/UserRepository.php`

    ```php
    <?php

    namespace App\Repositories\User;

    interface UserRepository
    {
        public function getAllUsers(): array;
        public function getUserById(int $id): array;
        public function createUser(array $request): array;
        public function updateUserById(array $request, array $userById): array;
        public function deleteUserById(int $id): array; // write this code
    }
    ```

-   Add new method in `UserRepositoryImplement.php` :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    // other code above...

    public function deleteUserById($id): array
    {
        try {
            DB::delete("DELETE FROM users WHERE id = ?", [$id]);

            return ["message" => sprintf("User ID : %d is deleted!", $id)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```

### Controllers

-   Now, open your `UserController.php`, add this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    // other code above...

    public function deleteUserById(int $id): JsonResponse
    {
        $userById = $this->userRepository->getUserById($id);

        if (array_key_exists("error", $userById)) {
            return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
        }

        $deletedUser = $this->userRepository->deleteUserById($userById[0]->id);

        if (array_key_exists("error", $deletedUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $deletedUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $deletedUser))->toArray(), Response::HTTP_OK);
    }
    ```

### Routes

-   On `api.php` folder, add new users route like this :

    > File: `routes/api.php`

    ```php
    // other route code before...

    // users
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserById']);
    Route::post('/users', [UserController::class, 'createUser']);
    Route::patch('/users/{id}', [UserController::class, 'updateUserById']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUserById']); // write this code
    ```
