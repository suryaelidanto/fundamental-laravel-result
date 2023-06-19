# Fetching Query with Eloquent

### Repositories

-   Create `Repositories` folder, inside it create another folder called `User`, then inside it create 2 files called `UserRepository.php` and `UserRepositoryImplement.php`

-   Then write this code in `UserRepository.php` :

    > File: `app/Repositories/User/UserRepository.php`

    ```php
    <?php

    namespace App\Repositories\User;

    interface UserRepository
    {
        public function getAllUsers(): array;
        public function getUserById(int $id): array;
    }
    ```

-   Write this code in `UserRepositoryImplement.php` :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
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
    ```

    Explanation : `UserRepository` file can be called as interface, basically it's just a contract that implemented in `UserRepositoryImplement`

### AppServiceProvider

-   Open your `AppServiceProvider.php` :

-   Then, import your repositories on top like this :

    > File : `app/Providers/AppServiceProvider.php`

    ```php
    use App\Repositories\User\UserRepository;
    use App\Repositories\User\UserRepositoryImplement;
    ```

-   Then, add bind your repositories like this, it's needed so you can access your repositories later in your controllers :

    ```php
    public function register()
    {
        $this->app->bind(UserRepository::class, UserRepositoryImplement::class);
    }
    ```

### Controllers

-   Now, open your `UserController.php`, write this code :

    > File: `app/Http/Controllers/UserController.php`

    ```php
    <?php

    namespace App\Http\Controllers;

    use App\DTO\Response\Result\ErrorResponse;
    use App\DTO\Response\Result\SuccessResponse;
    use App\Repositories\User\UserRepository;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Response;

    class UserController extends Controller
    {
        private $userRepository;

        public function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        public function getAllUsers(): JsonResponse
        {
            $allUsers = $this->userRepository->getAllUsers();

            if (array_key_exists("error", $allUsers)) {
                return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $allUsers["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json((new SuccessResponse(Response::HTTP_OK, $allUsers))->toArray(), Response::HTTP_OK);
        }

        public function getUserById(int $id): JsonResponse
        {
            $userById = $this->userRepository->getUserById($id);

            if (array_key_exists("error", $userById)) {
                return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
            }

            return response()->json((new SuccessResponse(Response::HTTP_OK, $userById))->toArray(), Response::HTTP_OK);
        }
    }
    ```

### Routes

-   On `api.php` folder, add users route like this :

    > File: `routes/api.php`

    ```php
    // other route code before...

    // users
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserById']);
    ```

-   Don't forget to import, it's become easier (auto import, snippets, etc) if you install the extension in vscode (or even better using IDE, like PHPStorm) :

    ```php
    use App\Http\Controllers\UserController;
    ```

-   Yes, you're right. After that complex setup, it has become much easier to add or modify our code for later.
