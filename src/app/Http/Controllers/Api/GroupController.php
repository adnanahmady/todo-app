<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Group;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function show(Group $group)
    {
        $group->load('tasks');

        return response()->json($group->tasks->pluck('body'), 200);
    }

    public function store(Request $request)
    {
        $group = auth()
            ->user()
            ->groups()
            ->create(['name' => $request->get('name')]);
        $group->createdBy(auth()->user());

        return response()->json($group, 201);
    }
}
