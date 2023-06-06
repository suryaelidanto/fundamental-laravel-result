# Make Hello World

### 1. Project Init

#### 1.1 Create project with composer :

```bash
composer create-project laravel/laravel project-name
```

#### 1.2 Create project with laravel installer :

```bash
laravel new project-name
```

### 2. Create your first hello world API

> File : `routes/api.php`

```php
Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello World! 😎'
    ]);
});
```

This code defines a route for a GET request to the '/hello' endpoint. When a user makes a GET request to this endpoint, the code will return a JSON response with a single key-value pair, where the key is 'message' and the value is 'Hello World! 😎'. This is a simple example of how to define a route and return a JSON response in Laravel.

### 3. Running

Running Your App with this command

```
php artisan serve
```

### 4. Result 
- Open your browser and type http://localhost:8000/api/hello

![Alt text](image.png)

Pretty cool right? 😎🔥

