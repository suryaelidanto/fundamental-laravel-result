# Routing

In Laravel, routing is pretty easy and straightforward. It is essential to have a solid understanding of REST API principles, including the utilization of HTTP methods like GET, POST, PATCH, DELETE, etc.

API, which stands for Application Programming Interface, acts as an interface that connects one application with another. It serves as a mediator between different applications, whether they are within the same platform or across multiple platforms.

-   Firstly, make `todos.json` in `root directory` like so :

> File : `todos.json`

```json
[]
```

-   For now, you can revise the code you previously wrote in `routes/api.php`

> File : `routes/api.php`

-   Package :

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
```

-   Create routes :

```php
Route::get('/todos', [TodoController::class, 'findTodos']);
Route::get('/todos/{id}', [TodoController::class, 'getTodo']);
Route::post('/todos', [TodoController::class, 'createTodo']);
Route::patch('/todos/{id}', [TodoController::class, 'updateTodo']);
Route::delete('/todos/{id}', [TodoController::class, 'deleteTodo']);
```

-   Create Todo class :

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
```

-   Create TodoController class :

```php
class TodoController
{
// all todo controller stored here...
}
```

-   Inside `TodoController` insert this code :

```php
private string $todosFile;

public function __construct()
{
    $this->todosFile = base_path("/todos.json");
}
```

This is for storing `todos.json` file path so we can use it later

-   Create `findTodos` method :

```php
public function findTodos()
{
    $todos = json_decode(File::get($this->todosFile));

    return response()->json([
        'data' => $todos
    ]);
}
```

-   Create `getTodo` method :

```php
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
```

-   Create `createTodo` method :

```php
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
```

-   Create `updateTodo` method :

```php
public function updateTodo(Request $request, $id)
{
    $json = $request->json()->all();

    $data = new Todo(
        $json['id'],
        $json['title'],
        $json['isDone']
    );

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
```

-   Create `deleteTodo` method :

```php
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
```

### Postman Structure

You can structure your Postman request like this:

![Alt text](image.png)
