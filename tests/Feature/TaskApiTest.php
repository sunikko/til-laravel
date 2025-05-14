<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use WithFaker;

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
     * Test POST /api/tasks - task creation with valid data
     * Creates a task with valid data, asserts HTTP 201 and JSON structure: ['message', 'task']. 
     */
    public function test_can_create_task_with_valid_data()
    {
        $test_name = 'Test Task';
        $test_description = 'This is a test description with more than 10 characters.';

        $taskData = [
            'name' => $test_name,
            'description' => $test_description,
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Task created'])
            ->assertJsonStructure(['message', 'task' => ['id', 'name', 'description', 'secure_token', 'created_at', 'updated_at']]);

        $this->assertDatabaseHas('tasks', [
            'name' => $test_name,
            'description' => $test_description,
        ]);

        $task = Task::latest()->first();
        $this->assertNotNull($task->secure_token);
        $this->assertTrue(Str::isUuid($task->secure_token));
    }

    /**
    * Test POST /api/tasks - task creation fails with missing data
    * Asserts HTTP 422 status and JSON validation errors for 'name' and 'description'.
    */
    public function test_create_task_fails_missing_data()
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description']);
    }

    /**
    * Test that task creation fails with validation errors for short name.
    * Asserts HTTP 422 status and JSON validation errors for 'name'.
    */
    public function test_create_task_fails_short_name()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => 'ab',
            'description' => 'This description is long enough.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
    * Test that task creation fails with validation errors for long name.
    * Asserts HTTP 422 status and JSON validation errors for 'name'.
    */
    public function test_create_task_fails_long_name()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => str_repeat('a', 101),
            'description' => 'This description is long enough.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
    * Test that task creation fails with validation errors for short description.
    * Asserts HTTP 422 status and JSON validation errors for 'description'.
    */
    public function test_create_task_fails_short_description()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => 'Valid Name',
            'description' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    /**
     * Test that task creation fails with validation errors for long description.
     * Asserts HTTP 422 status and JSON validation errors for 'description'.
     */
    public function test_create_task_fails_long_description()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => 'Valid Name',
            'description' => str_repeat('a', 7007),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
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

     /**
     * Test PUT /api/tasks/{id} - update task
     * Creates a task, updates it, asserts HTTP 200 and JSON structure: ['message', 'task'].
     */
    public function test_can_update_task()
    {
        $task = Task::factory()->create();
        $updatedData = [
            'name' => 'Updated Task Name',
            'description' => 'This is the updated description.',
            'secure_token' => (string) $task->secure_token,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task updated successfully'])
            ->assertJson(['task' => [
                'id' => $task->id,
                'name' => 'Updated Task Name',
                'description' => 'This is the updated description.',
                'secure_token' => (string) $task->secure_token,
            ]]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'description' => 'This is the updated description.',
            'secure_token' => (string) $task->secure_token,
        ]);
    }

    /**
     * Test PUT /api/tasks/{id} - update task not found
     * Asserts HTTP 404 and JSON message: 'No query results for model [App\Models\Task]'.
     */
    public function test_update_fails_not_found()
    {
        $validToken = Task::factory()->create()->secure_token;

        $updatedData = [
            'name' => 'Updated Task Name',
            'description' => 'This is the updated description.',
            'secure_token' => $validToken,
        ];

        $response = $this->putJson('/api/tasks/999', $updatedData);
        $response->assertStatus(404);
    }

    /**
     * Test PUT /api/tasks/{id} - update task fails validation
     * Creates a task, sends invalid data, asserts HTTP 422 and JSON validation errors.
     */
    public function test_update_fails_validation()
    {
        // Arrange: Create a task and prepare invalid update data
        $task = Task::factory()->create();
        $invalidData = [
            'name' => 'ab', // Too short
            'description' => 'short', // Too short
            'secure_token' => '', // Required
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'secure_token']);
    }


    /**
     * Test DELETE /api/tasks/{id} - delete task
     * Creates a task, sends DELETE request with secure token, asserts HTTP 204 and soft delete.
     */
     public function test_can_delete_task()
     {
         $task = Task::factory()->create();

         $response = $this->deleteJson("/api/tasks/{$task->id}", ['secure_token' => $task->secure_token]);
         $response->assertStatus(204);
 
         $this->assertSoftDeleted($task);
     }
 
 

     /** 
      * Test DELETE /api/tasks/{id} - delete task not found
      * Sends DELETE request to a non-existent task, asserts HTTP 404 and JSON error message.
      */
     public function test_delete_fails_not_found()
     {
        $response = $this->deleteJson('/api/tasks/999', ['secure_token' => (string) Str::uuid()]);
        $response->assertStatus(404);
     }
 
 
     /**
      * Test DELETE /api/tasks/{id} - delete task fails validation
      * Creates a task, sends DELETE request without secure token, asserts HTTP 422 and JSON validation error.
      */
     public function test_delete_fails_validation()
     {
         $task = Task::factory()->create();
         $response = $this->deleteJson("/api/tasks/{$task->id}", []);
 
         $response->assertStatus(422)
             ->assertJsonValidationErrors(['secure_token']);
     }
    
}
