<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * call app layout
     * where our vue app initialized
     */
    public function index()
    {
       return view('app');
    }

    /**
     * with this api get endpoint
     * list all tasks from db to vue datatable
     * used collection of resources for separation and data trasnformation
     */
    public function getTasks()
    {
        $tasks = Task::with('user')->get();
        return TaskResource::collection($tasks);
    }

    /**
     * add new task via this api post endpoint
     * we don't have users auth currently implemented
     * so we get a random user ID or create a new one and assign
     */
    public function store(StoreTaskRequest $request)
    {
        $randomUserId = User::inRandomOrder()->first()?->id ?? null;
        $request['user_id'] = $randomUserId;
        $task = Task::create($request->all());
        return new TaskResource($task);
    }
    
    /**
     * update a specific task via this api put endpoint
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        $task->updateOrFail($request->all());
        return new TaskResource($task);
    }

    /**
     * delete a specific task via this api delete endpoint
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response(null, 204);
    }

    /**
     * this method updates multiple rows at once based on the selected rows.
     * mark them as complete, with the current timestamp
     */
    public function massComplete(Request $request)
    {
        $ids = $request->request->all();
        if (!empty($ids)) {
            Task::whereIn('id', $ids)
                ->whereNull('completed_at')
                ->update([
                    'completed_at' => now(),
                    'used_time' => DB::raw('TIMESTAMPDIFF(SECOND, created_at, NOW())')
            ]);
        }
    
        return response()->noContent();
    }

    /**
     * this method delete multiple rows at once based on the selected rows.
     */
    public function massDestroy(Request $request)
    {
        $ids = $request->request->all();
        
        if (!empty($ids)) {
            Task::destroy($ids);
        }
        
        return response(null, 204);
    }
}
