<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_join_many_groups()
    {
        $user = factory(User::class)->create();
        $groups = factory(Group::class, 3)->create();

        $user->groups()->attach($groups[0]);
        $user->groups()->save($groups[2]);

        $this->assertTrue($user->groups->contains($groups[0]->id));
        $this->assertTrue($user->groups->contains($groups[2]->id));
        $this->assertFalse($user->groups->contains($groups[1]->id));
    }

    /** @test */
    public function it_must_checks_if_is_joined_to_group()
    {
        $user = factory(User::class)->create();
        $groups = factory(Group::class, 2)->create();
        $user->groups()->attach($groups->last());

        $this->assertFalse($user->doesJoinedTo($groups->first()->id));
        $this->assertTrue($user->doesJoinedTo($groups->last()->id));
    }

    /** @test */
    public function must_access_groups_through_pivot()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $user->groups()->attach($group);

        $this->assertTrue($user->groups->contains($group->id));
    }

    /** @test */
    public function can_find_its_owned_groups()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $user->groups()->attach($group, ['is_creator' => true]);

        $this->assertTrue(!! $user
             ->ownedGroups()
             ->first()
             ->groups
             ->first()
             ->pivot
             ->is_creator
        );
    }
}
