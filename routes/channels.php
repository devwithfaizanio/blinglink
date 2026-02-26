<?php

use App\Models\Community;
use App\Models\Connection;
use App\Models\ConnectionToMatchmaker;
use App\Models\ConnectionToMentor;
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


Broadcast::channel('connection.{fromId}.{toId}', function ($user, $fromId, $toId) {

    // User must be one of the two
    if ($user->id != $fromId && $user->id != $toId) {
        return false;
    }

    // Must be accepted connection
    return Connection::query()->where(function ($query) use ($fromId, $toId) {
        $query->where('requester_id', $fromId)
            ->where('requested_id', $toId);
    })
        ->orWhere(function ($query) use ($fromId, $toId) {
            $query->where('requester_id', $toId)
                ->where('requested_id', $fromId);
        })
        ->where('status', 'accepted')
        ->exists();
});

Broadcast::channel('mentor-chat.{fromId}.{toId}', function ($user, $fromId, $toId) {

    // User must be one of the two
    if ($user->id != $fromId && $user->id != $toId) {
        return false;
    }
    // Must be accepted connection
    return  ConnectionToMentor::query()->where(function ($query) use ($fromId, $toId) {
        $query->where('user_id', $fromId)
            ->where('mentor_id', $toId);
            })
        ->orWhere(function ($query) use ($fromId, $toId) {
            $query->where('user_id', $toId)
                ->where('mentor_id', $fromId);
        })
        ->exists();
});


Broadcast::channel('matchmaker-chat.{fromId}.{toId}', function ($user, $fromId, $toId) {

    // User must be one of the two
    if ($user->id != $fromId && $user->id != $toId) {
        return false;
    }
    // Must be accepted connection
    return  ConnectionToMatchmaker::query()->where(function ($query) use ($fromId, $toId) {
        $query->where('user_id', $fromId)
            ->where('matchmaker_id', $toId);
    })
        ->orWhere(function ($query) use ($fromId, $toId) {
            $query->where('user_id', $toId)
                ->where('matchmaker_id', $fromId);
        })
        ->exists();
});
