# Group routes

Group Routes are needed in API development to differentiate a route for API or for standard website link.

-   On `routes/api.php` file, declare Grouping Function for all Route

> File: `routes/api.php`

```php
Route::prefix("v1")->group(function () {

    // todos
    Route::get('/todos', [TodoController::class, 'findTodos']);
    Route::get('/todos/{id}', [TodoController::class, 'getTodo']);
    Route::post('/todos', [TodoController::class, 'createTodo']);
    Route::patch('/todos/{id}', [TodoController::class, 'updateTodo']);
    Route::delete('/todos/{id}', [TodoController::class, 'deleteTodo']);


    //other routes

});
```

In this code, we make a route group with the name `v1`, which means version 1, so when you access the API, you should access it like this (assume you use port 8000): `localhost:8000/api/v1/todos`

-   On `app/Http/Controllers` create `TodoController.php` file

> File: `app/Http/Controllers/TodoController.php`

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
```

-   Then migrate "all" of your `TodoController` code from `routes/api.php` to `app/Http/Controllers/TodoController.php`

```php
class Todo
{
    public string $id;
    public string $title;
    public bool $isDone;

    public function __construct($id, $title, $isDone)
    {
        $this->id = $id;
        $this->title = $title;
        $this->isDone = $isDone;
    }
}

class TodoController
{
    private string $todosFile;

    public function __construct()
    {
        $this->todosFile = base_path("/todos.json");
    }

    public function findTodos()
    {
        $todos = json_decode(File::get($this->todosFile));

        return response()->json([
            'message' => $todos
        ]);
    }

    public function getTodo($id)
    {
        $todo = null;
        $isGetTodo = false;

        $todos = json_decode(File::get($this->todosFile));

        foreach ($todos as $item) {
            if ($id === $item->id) {
                $isGetTodo = true;
                $todo = $item;
                break;
            }
        }

        if (!$isGetTodo) {
            return response()->json([
                "code" => 404,
                "message" => "ID : $id not found"
            ], 404);
        }

        return response()->json($todo);
    }

    public function createTodo(Request $request)
    {
        $todos = json_decode(File::get($this->todosFile));

        $todo = new Todo(
            $request->json()->get('id'),
            $request->json()->get('title'),
            $request->json()->get('isDone')
        );

        array_push($todos, $todo);

        File::put($this->todosFile, json_encode($todos));

        return response()->json([
            "code" => 201,
            'message' => 'Todo created successfully'
        ]);
    }

    public function updateTodo(Request $request, $id)
    {
        $data = $request->json()->all();
        $isGetTodo = false;

        $todos = json_decode(File::get($this->todosFile));

        foreach ($todos as $index => $item) {
            if ($id === $item->id) {
                $isGetTodo = true;
                $todos[$index] = $data;
                break;
            }
        }

        if (!$isGetTodo) {
            return response()->json([
                "code" => 404,
                "message" => "ID : $id not found"
            ], 404);
        }

        File::put($this->todosFile, json_encode($todos));

        return response()->json($data);
    }

    public function deleteTodo($id)
    {
        $isGetTodo = false;
        $index = 0;

        $todos = json_decode(File::get($this->todosFile));

        foreach ($todos as $idx => $item) {
            if ($id === $item->id) {
                $isGetTodo = true;
                $index = $idx;
                break;
            }
        }

        if (!$isGetTodo) {
            return response()->json([
                "code" => 404,
                "ID : $id not found"
            ], 404);
        }

        array_splice($todos, $index, 1);
        File::put($this->todosFile, json_encode($todos));

        return response()->json([
            "code" => 204,
            'message' => 'Todo deleted successfully'
        ]);
    }
}
```

-   Change the import in `routes/api.php` like this :

> File: `routes/api.php`

```php
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
```

So it's simply calling `TodoController` from `Controllers`

### Trying in Postman

![Alt text](image.png)

Notice, it has v1 after api so it's become `/api/v1/`