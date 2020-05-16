<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Group;
use App\Task;
use App\User;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_visiting_groups_main_returns_all_tasks_to_group()
    {
        $this->be(factory(User::class)->create());
        $group = factory(Group::class)->create();
        $tasks = factory(Task::class, 3)->create(['group_id' => $group->id]);
        $nonRelatedTask = factory(Task::class)->create();

        $response = $this->json(
            'GET',
            route(
                'groups.show',
                ['group' => $group->id]
            )
        )->json();
        $tasks->map(function ($task) use ($response) {
            $this->assertContains($task['body'], $response);
        });
        $this->assertNotContains($nonRelatedTask->body, $response);
    }

    /** @test */
    public function when_creating_group_authenticated_user_joins_as_creator()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $data = ['name' => 'Some group'];
        $response = $this->json('POST', route('groups.store', $data));
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('group_user', ['is_creator' => true]);
    }
}
