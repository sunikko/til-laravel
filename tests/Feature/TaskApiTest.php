<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase; // Dosen't work with MongoDB

    /**
     * Set up the test environment.
     * Truncate the Task collection before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Task::truncate();
    }

     /**
     * Test POST /api/tasks - task creation
     * Asserts HTTP 201 status, JSON structure: ['message', 'task'] and 'message' => 'Task created'.
     */
    public function test_can_create_task()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => $this->faker->name,
            'description' => $this->faker->text(200),
            'secure_token' => $this->faker->uuid,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'task'])
                    ->assertJson(['message' => 'Task created',]);
    }

     /**
     * Test GET /api/tasks - list tasks
     * Creates 3 tasks, asserts HTTP 200 status and JSON count of 3.
     */
    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }


    /**
     * Test GET /api/tasks/{id} - show task
     * Creates a task, asserts HTTP 200 and JSON structure: ['id', 'name', 'description', 'secure_token'].
     */
    public function test_can_show_task()
    {
        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'secure_token' => 'test-token',
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $task->id,
                'name' => 'Test Task',
                'description' => 'Test Description',
                'secure_token' => 'test-token',
                'created_at' => $task->created_at->toISOString(),
                'updated_at' => $task->updated_at->toISOString(),
            ]);
    }

    /**
     * Test GET /api/tasks/{id} - show task not found
     * Asserts HTTP 404 status and JSON message: 'No query results for model [App\Models\Task]'.
     */
    public function test_show_returns_404_if_task_not_found()
    {
        $response = $this->getJson('/api/tasks/999');
        $response->assertStatus(404);
    }
}
