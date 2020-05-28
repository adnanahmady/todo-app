<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Task;
use App\User;
use App\Group;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_be_done()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $group = factory(Group::class)->create();
        $task = factory(Task::class)->create([
            'owner_id' => auth()->id(),
            'group_id' => $group->id
        ]);
        $group->users()->attach(auth()->id());

        $response = $this->json('PUT', route('task.done', [
            'group' => $group,
            'task' => $task
        ]));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($task->fresh()->finish_date);
    }
}
