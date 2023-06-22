# Insert Query with Query Builder

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
        public function createUser(array $request): array; // write this code
    }
    ```

-   Add new method in `UserRepositoryImplement.php` :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    // other code above...

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
    ```

### Controllers

-   Now, open your `UserController.php`, add this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    use App\DTO\Request\User\CreateUserRequest;
    use Illuminate\Http\Request;
    ```

-   Then, add this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    // other code above...

    public function createUser(Request $request): JsonResponse
    {
        $validatedRequest =  (new CreateUserRequest($request->all()))->validate();

        if (array_key_exists("error", $validatedRequest)) {
            return response()->json((new ErrorResponse(Response::HTTP_BAD_REQUEST, $validatedRequest["error"]))->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $createdUser = $this->userRepository->createUser($request->all());

        if (array_key_exists("error", $createdUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $createdUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $createdUser))->toArray(), Response::HTTP_OK);
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
    Route::post('/users', [UserController::class, 'createUser']); // write this code
    ```

-   Notice that here we use `Route::post` not `Route::get` for creating user, because we want to use `POST` HTTP request in postman later.
