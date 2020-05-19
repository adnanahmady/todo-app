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

        $user->joinedGroups()->attach($groups[0]);
        $user->joinedGroups()->save($groups[2]);

        $this->assertTrue($user->joinedGroups->contains($groups[0]->id));
        $this->assertTrue($user->joinedGroups->contains($groups[2]->id));
        $this->assertFalse($user->joinedGroups->contains($groups[1]->id));
    }

    /** @test */
    public function it_must_checks_if_is_joined_to_group()
    {
        $user = factory(User::class)->create();
        $groups = factory(Group::class, 2)->create();
        $user->joinedGroups()->attach($groups->last());

        $this->assertFalse($user->doesJoinedTo($groups->first()->id));
        $this->assertTrue($user->doesJoinedTo($groups->last()->id));
    }
}
