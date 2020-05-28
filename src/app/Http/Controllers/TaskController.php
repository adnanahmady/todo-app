<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Group;
use App\Events\TaskIsDone;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Events\NewTaskDidCreateEvent;

class TaskController extends Controller
{
    /**
     * returns all tasks
     *
     */
    public function index()
    {
        return response()->json(Task::get()->pluck('body'));
    }
    
    /**
     * stores new task to database
     *
     * @param TaskRequest $request
     * @param Group $group
     *
     * @return TaskResource
     */
    public function store(TaskRequest $request, Group $group)
    {
        $task = $group->tasks()->create([
            'owner_id' => auth('web')->id(),
            'body' => $request->get('body')
        ]);
        broadcast(new NewTaskDidCreateEvent(auth()->user(), $task));

        return new TaskResource($task);
    }

    /**
     * marks task as finished
     *
     * @param Group $group
     * @param Task $task
     *
     * @return TaskResource
     */
    public function update(Group $group, Task $task)
    {
        if (! $task->isDone()) {
            $task->done();
            broadcast(new TaskIsDone($task));
        }

        return new TaskResource($task);
    }
}
