<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the Task model relationship with User is defined correctly.
     *
     * @return void
     */
    public function test_task_belongs_to_user()
    {
        $task = new Task();

        $relation = $task->user();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
        $this->assertEquals('user_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
    }

    /**
     * Test if the Task model allows mass assignment of the specified attributes.
     *
     * @return void
     */
    public function test_task_mass_assignment()
    {
        $task = new Task();
        $fillable = [
            'description',
            'estimated_time',
            'completed_at',
            'used_time',
            'user_id'
        ];
        $this->assertEquals($fillable, $task->getFillable());
    }

    /**
     * Test if DatabaseSeeder seeds data into the tasks table.
     * and with the correct count
     * @return void
     */
    public function test_task_seeder()
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $tasksCount = Task::count();
        $this->assertEquals(50, $tasksCount);
    }

    /**
     * Test validation rules for StoreTaskRequest.
     *
     * @return void
     */
    public function testValidationRules()
    {
        $request = new StoreTaskRequest();

        $data = [
            'description' => 'Task description',
            'estimated_time' => 10,
            'user_id' => null,
        ];

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());

        $data['description'] = str_repeat('a', 256); // Exceeds the maximum length of 255 characters

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());

        $this->assertEquals([
            'description' => ['The description field must not be greater than 255 characters.'],
        ], $validator->errors()->messages());
    }
}