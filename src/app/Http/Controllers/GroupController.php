<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class GroupController extends Controller
{
    /**
     * loads groups page
     *
     * @param Group $group
     */
    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    /**
     * shows create group page
     *
     * @return View
     */
    public function create()
    {
        return view('groups.create');
    }
}
