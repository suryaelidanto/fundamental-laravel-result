<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/todos', [TodoController::class, 'findTodos']);
Route::get('/todos/{id}', [TodoController::class, 'getTodo']);
Route::post('/todos', [TodoController::class, 'createTodo']);
Route::patch('/todos/{id}', [TodoController::class, 'updateTodo']);
Route::delete('/todos/{id}', [TodoController::class, 'deleteTodo']);

class TodoController
{
    private $todosFile;

    public function __construct()
    {
        $this->todosFile = base_path("/todos.json");
    }

    public function findTodos()
    {
        $todos = json_decode(file_get_contents($this->todosFile));

        return response()->json([
            'message' => $todos
        ]);
    }

    public function getTodo($id)
    {
        $todo = null;
        $isGetTodo = false;

        $todos = json_decode(file_get_contents($this->todosFile));

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
        $todos = json_decode(file_get_contents($this->todosFile));

        $todo = [
            'id' => $request->id,
            'title' => $request->title,
            'isDone' => false
        ];

        array_push($todos, $todo);

        file_put_contents($this->todosFile, json_encode($todos));

        return response()->json([
            "code" => 201,
            'message' => 'Todo created successfully'
        ]);
    }

    public function updateTodo(Request $request, $id)
    {
        $data = $request->json()->all();
        $isGetTodo = false;

        $todos = json_decode(file_get_contents($this->todosFile));

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

        file_put_contents($this->todosFile, json_encode($todos));

        return response()->json($data);
    }

    public function deleteTodo($id)
    {
        $isGetTodo = false;
        $index = 0;

        $todos = json_decode(file_get_contents($this->todosFile));

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
        file_put_contents($this->todosFile, json_encode($todos));

        return response()->json([
            "code" => 204,
            'message' => 'Todo deleted successfully'
        ]);
    }
}