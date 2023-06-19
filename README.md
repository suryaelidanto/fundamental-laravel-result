# Update Data with Eloquent

### Repositories

-   Modify your `updateUserById` method to use eloquent, like this :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
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
    ```
