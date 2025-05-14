<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    // List all tasks
    public function index()
    {
        return response()->json(Task::all(), 200);
    }

    // Create a new task
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:10|max:1000',
        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'secure_token' => (string) Str::uuid(), // Generate a unique token, return LazyUuidFromString object
        ]);

        $task->save();

        

        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }

    // Get a specific task
    public function show($id)
    {
        $task = Task::findOrFail($id); // Find the task by ID, or fail with 404(ModelNotFoundException)

        return response()->json($task, 200);
    }

    // Update a specific task
    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Update task not implemented yet'], 501);
    }

    // Delete a specific task
    public function destroy(Request $request, string $id)
    {
        return response()->json(['message' => 'Delete task not implemented yet'], 501);
    }

}
