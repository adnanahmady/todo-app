<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class: Task
 *
 * @see Model
 */
class Task extends Model
{
    /**
     * specifies whitch columns are allowed to be filled
     *
     * @var array
     */
    protected $fillable = ['group_id', 'body', 'owner_id'];

    /**
     * returns the tasks writer
     *
     * @return \App\User
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * returns the group that task belongs to
     *
     * @return \App\Task
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
