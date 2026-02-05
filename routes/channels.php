<?php

use App\Models\Community;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('community.{communityId}', function ($user, $communityId) {

    $community = Community::find($communityId);

    if (!$community) {
        return false;
    }

    // allow creator or members only
    return $community->isCreator($user->id) || $community->isMember($user->id);
});
