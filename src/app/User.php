<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class: User
 *
 * @see Authenticatable
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * returns users owned groups
     *
     * @return \App\Group
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    /**
     * returns groups that user is joined in
     *
     * @return App\Group
     */
    public function joinedGroups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    /**
     * returns all users created tasks regaurdless of there group
     *
     * @return \App\Tasks
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }
}
