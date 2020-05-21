<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGroupRequest;

class GroupController extends Controller
{
    /**
     * shows all user joined groups
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        auth()->user()->load('groups');

        return response()->json(
            auth()->user()->groups,
            200
        );
    }

    /**
     * returns a list of groups tasks
     *
     * @param Group $group
     *
     * @return Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $group->load('tasks');

        return response()->json($group->tasks->pluck('body'), 200);
    }

    /**
     * creates a group and sets
     * authenticated user as groups creator
     *
     * @param CreateGroupRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(CreateGroupRequest $request)
    {
        $group = Group::create(['name' => $request->get('name')]);
        $group->createdBy(auth()->user());

        return response()->json($group, 201);
    }
}
