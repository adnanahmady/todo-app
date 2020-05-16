<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Events\NewTaskDidCreateEvent;
use App\Group;
use App\User;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::get()->pluck('body'));
    }
    
    public function store(Request $request, Group $group)
    {
        $task = $group->tasks()->create([
            'owner_id' => auth('web')->id(),
            'body' => $request->get('body')
        ]);
        event(new NewTaskDidCreateEvent(auth('web')->user(), $task));

        return response()->json($task, 201);
    }
}
