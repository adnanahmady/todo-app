<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('groups.{groupId}', function ($user, $groupId) {
    if ($user->doesJoinedTo($groupId)) {
        return ['name' => $user->name];
    }

    return false;
});
