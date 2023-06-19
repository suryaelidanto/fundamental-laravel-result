# Insert Data with Eloquent

### Repositories

-   Modify your `createUser` method to use eloquent, like this :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
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
            ]); // modify to this

            return ["message" => sprintf("User email : '%s' is created!", $email)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```

-   And, add this `$fillable` in `User` models :

    > File : `app/Models/User.php`

    ```php
    class User extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name', 'email', 'password',
        ]; // add this variable
    }
    ```

-   The `$fillable` variable allows you to declare which columns can be filled.
