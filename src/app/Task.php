<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    protected $fillable = [
        'group_id',
        'body',
        'owner_id',
        'finish_date'
    ];

    /**
     * casts columns to date format
     *
     * @var array
     */
    protected $dates = [
        'finish_date'
    ];

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

    /**
     * checks if task is finished
     *
     * @return bool
     */
    public function isDone()
    {
        return !! $this->finish_date;
    }

    /**
     * marks task as finished
     */
    public function done()
    {
        $this->update(['finish_date' => Carbon::now()]);
    }
}
