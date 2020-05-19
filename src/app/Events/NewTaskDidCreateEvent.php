<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewTaskDidCreateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * App\User
     *
     * @var mixed
     */
    protected $user;

    /**
     * App\Task
     *
     * @var mixed
     */
    protected $task;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $task)
    {
        $this->user = $user;
        $this->task = $task;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('groups.'.$this->task->group->id);
    }

    /**
     * specifies events payload
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => $this->user->only('id', 'name', 'email'),
            'task' => collect($this->task)->except(
                'owner_id',
                'created_at',
                'updated_at',
                'group_id',
                'group.owner_id',
                'group.updated_at',
                'group.created_at'
            )
        ];
    }
}
