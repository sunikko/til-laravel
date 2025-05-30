<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    protected $taskModel;

    public function __construct(Task $taskModel)
    {
        $this->taskModel = $taskModel;
    }

    /**
     * List all tasks.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response containing all tasks.
     *
     * Example success response (HTTP 200):
     * [
     * {
     * "id": 1,
     * "name": "Task One",
     * "description": "Description for task one.",
     * "secure_token": "uuid-example-1",
     * "created_at": "2025-05-14T...",
     * "updated_at": "2025-05-14T...",
     * "deleted_at": null
     * },
     * {
     * "id": 2,
     * "name": "Task Two",
     * "description": "Description for task two.",
     * "secure_token": "uuid-example-2",
     * "created_at": "2025-05-14T...",
     * "updated_at": "2025-05-14T...",
     * "deleted_at": null
     * },
     * // ... more tasks
     * ]
     *
     * Example error response (HTTP 500):
     * {
     * "message": "Server error occurred while fetching tasks."
     * }
     */
    public function index()
    {
        $tasks = $this->taskModel->all();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the new task data.
     * Example success response (HTTP 201):
     * {
     * "message": "Task created",
     * "task": {
     * "id": 123,
     * "name": "My New Task",
     * "description": "Details about the task.",
     * "secure_token": "uuid-example",
     * "created_at": "2025-05-14T...",
     * "updated_at": "2025-05-14T..."
     * }
     * }
     * Example validation error response (HTTP 422):
     * {
     * "message": "The given data was invalid.",
     * "errors": {
     * "name": [
     * "The name field is required."
     * ],
     * "description": [
     * "The description field is required.",
     * "The description must be at least 10 characters."
     * ]
     * }
     * }
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskModel->create($request->validated() + [
            'secure_token' => $request->secure_token,
        ]);

        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the task data.
     * Example success response (HTTP 200):
     * {
     * "id": 123,
     * "name": "Existing Task",
     * "description": "More details here.",
     * "secure_token": "another-uuid",
     * "created_at": "2025-05-14T...",
     * "updated_at": "2025-05-14T..."
     * }
     * Example not found error response (HTTP 404): ModelNotFoundException
     * {
     * "message": "No query results for model [App\\Models\\Task] 1."
     * }
     */
    public function show($id)
    {
        $task = $this->taskModel->findOrFail($id);

        return response()->json($task, 200);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the updated task data.
     *
     * Example success response (HTTP 200):
     * {
     * "message": "Task updated successfully",
     * "task": {
     * "id": 1,
     * "name": "Updated Task Name",
     * "description": "This is the updated description.",
     * "secure_token": "uuid-example-1",
     * "created_at": "2025-05-14T...",
     * "updated_at": "2025-05-14T...",
     * "deleted_at": null
     * }
     * }
     *
     * Example validation error response (HTTP 422):
     * {
     * "message": "The given data was invalid.",
     * "errors": {
     * "name": [
     * "The name must be at least 3 characters."
     * ],
     * "description": [
     * "The description must be at least 10 characters."
     * ],
     * "secure_token": [
     * "The secure token field is required."
     * ]
     * }
     * }
     *
     * Example not found error response (HTTP 404):
     * {
     * "message": "No query results for model [App\\Models\\Task]."
     * }
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskModel->where('id', $id)
                ->where('secure_token', $request->secure_token)
                ->firstOrFail();

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ], 200);
    }

    /**
     * Remove the specified task from storage (soft delete).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse Returns an empty response on successful deletion.
     *
     * Example success response (HTTP 204):
     * // Empty response
     *
     * Example validation error response (HTTP 422):
     * {
     * "message": "The given data was invalid.",
     * "errors": {
     * "secure_token": [
     * "The secure token field is required."
     * ]
     * }
     * }
     *
     * Example not found error response (HTTP 404):
     * {
     * "message": "No query results for model [App\\Models\\Task]."
     * }
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([
            'secure_token' => 'required|string',
        ]);

        $task = $this->taskModel->where('id', $id)
            ->where('secure_token', $request->secure_token)
            ->firstOrFail();


        $task->delete();
        return response()->json(null, 204);
    }

}
