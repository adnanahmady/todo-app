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
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $group = factory(Group::class)->create();
        $tasks = factory(Task::class, 3)->create(['group_id' => $group->id]);
        $group->users()->attach(auth()->user());
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
        $response = $this->json('POST', route('groups.store'), $data);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('group_user', ['is_creator' => true]);
    }

    /** @test */
    public function user_can_see_its_joined_groups()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());
        $groups = factory(Group::class, 2)->create();
        $notJoinedGroups = factory(Group::class, 2)->create();
        $groups->each(function ($group) {
            auth()->user()->groups()->attach($group->id);
        });
        $response = $this->json('GET', route('groups.list'));

        $this->assertEquals(200, $response->getStatusCode());

        (function ($groups, $notJoinedGroups, $response) {
            $groups->map(function ($group) use ($response) {
                $this->assertStringContainsString($group->name, $response);
            });
            $notJoinedGroups->map(function ($group) use ($response) {
                $this->assertStringNotContainsString($group->name, $response);
            });
        })($groups, $notJoinedGroups, json_encode($response->json()));
    }

    /** @test */
    public function create_page_must_exists()
    {
        $this->be(factory(User::class)->create());
        $this->get(route('groups.create'))
            ->assertStatus(200);
    }
}
