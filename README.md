# Update Query with Query Builder

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
        public function updateUserById(array $request, array $userById): array; // write this code
    }
    ```

-   Add new method in `UserRepositoryImplement.php` :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    // other code above...

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

            DB::update("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?", [$name, $email, $password, $userById["id"]]);

            return ["message" => sprintf("User ID : '%s' is updated!", $userById["id"])];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```

### Controllers

-   Now, open your `UserController.php`, add this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    use App\DTO\Request\User\UpdateUserRequest;
    ```

-   Then, add this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    // other code above...

    public function updateUserById(Request $request, int $id): JsonResponse
    {

        $userById = $this->userRepository->getUserById($id);

        if (array_key_exists("error", $userById)) {
            return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
        }

        $validatedRequest = (new UpdateUserRequest($request->all()))->validate();

        if (array_key_exists("error", $validatedRequest)) {
            return response()->json((new ErrorResponse(Response::HTTP_BAD_REQUEST, $validatedRequest["error"]))->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $updatedUser = $this->userRepository->updateUserById($request->all(), $userById);

        if (array_key_exists("error", $updatedUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $updatedUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $updatedUser))->toArray(), Response::HTTP_OK);
    }
        
    // other code below...
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
    Route::patch('/users/{id}', [UserController::class, 'updateUserById']); // write this code
    ```
