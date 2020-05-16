<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $users = factory(\App\User::class, 3)->create();
        $users->each(function ($user) {
            $groups = factory(\App\Group::class, 3)->create(['owner_id' => $user->id]);
            $groups->each(function ($group) use ($user) {
                factory(\App\Task::class, 3)->create([
                    'owner_id' => $user->id,
                    'group_id' => $group->id
                ]);
            });
        });
    }
}
