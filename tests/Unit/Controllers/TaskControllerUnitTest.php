<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Api\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;
use Mockery;

class TaskControllerUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Manually clear MongoDB collection instead of use RefreshDatabase;
        Task::truncate(); 
    }

    /**
     * @test: index()
     * 
     */
    protected function tearDown(): void
    {
        Mockery::close();  // Mockery를 썼으면 꼭 close() 해줘야 함
        parent::tearDown();
    }

    public function testIndexReturnsAllTasks()
    {
        // Define expected values
        $expectedName1 = 'New Task 1';
        $expectedName2 = 'New Task 2';

        // Prepare mock data
        $mockTasks = collect([
            ['name' => $expectedName1, 'description' => 'Mock Description 1', 'secure_token' => 'token1'],
            ['name' => $expectedName2, 'description' => 'Mock Description 2', 'secure_token' => 'token2'],
        ]);

        // Mock Task model
        $taskMock = Mockery::mock(Task::class);
        $taskMock->shouldReceive('all')->once()->andReturn($mockTasks);

        // Inject mocked model into controller
        $controller = new TaskController($taskMock);

        // Call the index method
        $response = $controller->index();

        // Assert
        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertCount(2, $data);
        $this->assertEquals($expectedName1, $data[0]['name']);
        $this->assertEquals($expectedName2, $data[1]['name']);
    }


    /**
     * @test: Store()
     *
     */
    public function testStoreCreatesNewTask()
    {
        // Define expected values
        $expectedName = 'New Task';
        $expectedDescription = 'This is a new task description';
        $expectedToken = 'token123';

        // Prepare mock request data
        $requestData = [
            'name' => $expectedName,
            'description' => $expectedDescription,
        ];

        // Prepare a fake Task object to be returned from create()
        $createdTask = (object) array_merge($requestData, [
            'secure_token' => $expectedToken,
        ]);

        // Mock the Task model
        $taskMock = Mockery::mock(Task::class);
        $taskMock->shouldReceive('create')
                ->once()
                ->with(Mockery::on(function ($arg) use ($requestData) {
                    return $arg['name'] === $requestData['name']
                            && $arg['description'] === $requestData['description']
                            && isset($arg['secure_token']);
                }))
                ->andReturn($createdTask);

        // Create a mock request with input data
        $request = StoreTaskRequest::create('/api/tasks', 'POST', $requestData);
        
        // Important: Apply container and redirector for validation to work
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved(); // need to manually call validateResolved

        $request->merge(['secure_token' => $expectedToken]);


        // Instantiate controller with mock model
        $controller = new TaskController($taskMock);

        // Call store method
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertEquals('Task created', $responseData['message']);
        $this->assertEquals($expectedName, $responseData['task']['name']);
        $this->assertEquals($expectedDescription, $responseData['task']['description']);
        $this->assertEquals($expectedToken, $responseData['task']['secure_token']);
    }



    /**
     * 
     * @test: update()
     */
    public function testUpdateModifiesTask()
    {
        $taskId = 'abc123';
        $inputData = [
            'name' => 'Updated Task',
            'description' => 'Updated description text',
            'secure_token' => 'token123',
        ];

        $taskMockInstance = Mockery::mock();
        $taskMockInstance->shouldReceive('update')
                        ->once()
                        ->with([
                            'name' => $inputData['name'],
                            'description' => $inputData['description'],
                        ])
                        ->andReturnTrue();

        // Task::where(...)->where(...)->firstOrFail() chain mock
        $taskMock = Mockery::mock(Task::class);
        $taskMock->shouldReceive('where')
                ->once()
                ->with('id', $taskId)
                ->andReturnSelf();
        $taskMock->shouldReceive('where')
                ->once()
                ->with('secure_token', $inputData['secure_token'])
                ->andReturnSelf();
        $taskMock->shouldReceive('firstOrFail')
                ->once()
                ->andReturn($taskMockInstance);

        $controller = new TaskController($taskMock);

        $request = \App\Http\Requests\UpdateTaskRequest::create('/api/tasks/' . $taskId, 'PUT', $inputData);
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        $response = $controller->update($request, $taskId);

        // Assert 
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertEquals('Task updated successfully', $responseData['message']);
    }

    /**
     * @test: destroy()
     *
     */
    public function testDestroyDeletesTask()
    {
        $taskId = 'abc123';
        $inputData = ['secure_token' => 'token123'];

        $taskMockInstance = Mockery::mock();
        $taskMockInstance->shouldReceive('delete')
                        ->once()
                        ->andReturnTrue();

        // Task::where(...)->where(...)->firstOrFail() chain mock
        $taskMock = Mockery::mock(Task::class);
        $taskMock->shouldReceive('where')
                ->once()
                ->with('id', $taskId)
                ->andReturnSelf();
        $taskMock->shouldReceive('where')
                ->once()
                ->with('secure_token', $inputData['secure_token'])
                ->andReturnSelf();
        $taskMock->shouldReceive('firstOrFail')
                ->once()
                ->andReturn($taskMockInstance);

        $controller = new TaskController($taskMock);

        $request = new \Illuminate\Http\Request();
        $request->replace($inputData);

        $response = $controller->destroy($request, $taskId);

        // Assert
        $this->assertEquals(204, $response->getStatusCode());
    }
    
    
}

?>