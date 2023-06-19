# Delete Data with Eloquent

### Repositories

-   Modify your `deleteUserById` method to use eloquent, like this :

    > File: `app/Repositories/User/UserRepositoryImplement.php`

    ```php
    public function deleteUserById(int $id): array
    {
        try {
            $this->model->where("id", $id)->delete(); // modify like this

            return ["message" => sprintf("User ID : %d is deleted!", $id)];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    ```
