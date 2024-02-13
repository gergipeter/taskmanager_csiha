<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\TaskController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testGetTasks()
    {
        $controller = new TaskController();
        $response = $controller->getTasks();

        $this->assertNotNull($response);
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response);
    }
    public function testStore()
    {
        $user = User::factory()->create();

        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'estimated_time' => 60*60,
            'user_id' => $user->id
        ];
    
        $storeTaskRequest = new \App\Http\Requests\StoreTaskRequest($taskData);
        $controller = new TaskController();
        $response = $controller->store($storeTaskRequest);
    
        $this->assertNotNull($response);
        $this->assertInstanceOf(\App\Http\Resources\TaskResource::class, $response);
        $this->assertInstanceOf(Task::class, $response->resource);
    }

    public function testMassDestroy()
    {
        $tasks = Task::factory()->count(3)->create();
        $ids = $tasks->pluck('id')->toArray();
        $request = Request::create('/tasks/mass-destroy', 'POST', ['ids' => implode(",", $ids)]);
        $controller = new TaskController();
        $response = $controller->massDestroy($request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}