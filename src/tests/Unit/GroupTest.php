<?php

namespace Tests\Unit;

use App\User;
use App\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * it tests that when a group is created
     * does its slug gets set by its name or not
     *
     * @test
     */
    public function with_creating_a_group_the_slug_field_must_automatically_be_set()
    {
        $groups = factory(Group::class, 3)->create([
            'name' => 'Group name'
        ]);

        $this->assertEquals('group-name', $groups[0]->slug);
        $this->assertEquals('group-name-1', $groups[1]->slug);
        $this->assertEquals('group-name-2', $groups[2]->slug);
    }

    /** @test */
    public function a_group_can_join_a_user_into_it_self()
    {
        $users = factory(User::class, 2)->create();
        $group = factory(Group::class)->create();

        $group->users()->attach($users[0]->id);

        $this->assertTrue($group->users->contains($users[0]->id));
        $this->assertTrue($users[0]->groups->contains($group->id));
        $this->assertFalse($group->users->contains($users[1]->id));
    }

    /** @test */
    public function the_group_adds_the_user_once()
    {
        $user = factory(User::class)->create();
        $group = $user->groups()->create(['name' => 'Some name.']);
        $group->createdBy($user);
        $group->createdBy($user);
        $this->assertCount(1, $group->users()->get());
    }
}
