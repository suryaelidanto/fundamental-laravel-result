# Fetching Data with Eloquent

### Eloquent Definition

Eloquent is Laravel's ORM (Object-Relational Mapping) that simplifies database interactions. It allows you to work with PHP objects to perform CRUD operations on database tables. Eloquent provides a clean syntax, relationships management, querying options, and convenient methods for retrieving and manipulating data. It streamlines database operations in Laravel, making development easier and more efficient.

### Why using ORM instead of Query Builder?

Eloquent ORM simplifies database interactions in Laravel with a cleaner and more expressive syntax. It provides features like relationships management, model events, and automatic timestamp handling not available with query builders. Eloquent can streamline database operations in Laravel and make development more efficient.

### Repositories

-   Firstly, import `User` model like this:

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    use App\Models\User;
    ```

-   Then, on top of all method, inside class, write this code :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    ```

-   Modify your `getAllUsers` method to use eloquent, like this :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    // other code above...

    public function getAllUsers(): array
    {
        try {
            return $this->model->all()->toArray(); // modify this line
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```

-   Modify your `getUserById` method to use eloquent, like this :

    ```php
    public function getUserById(int $id): array
    {
        try {
            $user = $this->model->find($id); // modify this line

            if (empty($user)) {
                return ["error" => "User not found"];
            }

            $user = $user->toArray(); // add this line

            return $user;
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```
