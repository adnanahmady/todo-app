<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class: Group
 *
 * @see Model
 */
class Group extends Model
{
    /**
     * specifies whitch columns are allowed to be filled
     *
     * @var array
     */
    protected $fillable = ['slug', 'name'];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->slug = $model->name;
        });
    }

    /**
     * returns groups tasks
     *
     * @return \App\Task
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'group_id');
    }
    
    /**
     * returns the user that create's/own's
     *
     * @return \App\User
     */
    public function scopeOwner($query)
    {
        return $query->with(['users' => function ($query) {
            $query->where('is_creator', true);
        }]);
    }

    /**
     * returns users that joined to the group
     *
     * @return App\User
     */
    public function users()
    {
        return $this
            ->belongsToMany(User::class)
            ->withPivot('is_creator')
            ->withTimestamps();
    }

    /**
     * adds user to group as groups creator
     *
     * @param User $user
     */
    public function createdBy(User $user)
    {
        if (! $this->users()->where('user_id', $user->id)->exists()) {
            $this->users()->attach($user->id, ['is_creator' => true]);
        }
    }

    /**
     * checks if slug has no assigned value
     * (left with null value)
     * then will set slug by calling responsible method
     *
     * @param mixed $value
     */
    public function setSlugAttribute($value)
    {
        if ($this->whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * increments slugs number
     *
     * @param mixed $slug
     */
    public function incrSlug($slug)
    {
        $slugs = $this->whereName($this->name)
                      ->latest('id')
                      ->limit(3)
                      ->pluck('slug');
        $max = explode('-', count($slugs) > 1 ? $slugs->first() : 0);

        if (is_numeric($number = array_pop($max))) {
            return "{$slug}-" . ($number + 1);
        }

        return "{$slug}-1";
    }
}
