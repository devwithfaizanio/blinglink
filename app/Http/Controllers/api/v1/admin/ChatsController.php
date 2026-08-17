<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\chats\getConnectionChatRequest;
use App\Http\Requests\api\v1\admin\chats\getMatchmakerChatRequest;
use App\Http\Requests\api\v1\admin\chats\getMentorChatRequest;
use App\Http\Resources\api\v1\admin\chats\getConnectionResource;
use App\Http\Resources\api\v1\admin\chats\getMatchmakerConnectionResource;
use App\Http\Resources\api\v1\admin\chats\getMentorConnectionResource;
use App\Http\Resources\api\v1\connectionChat\ChatResource as ConnectionChatResource;
use App\Http\Resources\api\v1\matchmakerChat\ChatResource as matchmakerChatResource;
use App\Http\Resources\api\v1\mentorChat\ChatResource as mentorChatResource;
use App\Http\Resources\api\v1\connectionChat\ChatUserListResource;
use App\Models\Connection;
use App\Models\ConnectionChat;
use App\Models\ConnectionToMatchmaker;
use App\Models\ConnectionToMentor;
use App\Models\MatchMakerChat;
use App\Models\MentorChat;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Admin · Chats', weight: 2)]

class ChatsController extends Controller
{
    //connectionChatsList
    public function connectionChatsUserList(){
        $userIds = Connection::query()->where('status','accepted')->get();
        return $this->success(
            message: 'Successfully',
            data: getConnectionResource::collection($userIds),
        );
    }

    public function connectionChatList(getConnectionChatRequest $request){

        $limit = (int) $request->input('limit', 20);

        $fromUserId = $request->requester_id;
        $toUserId = $request->requested_id;

        $chats = ConnectionChat::query()
            ->where(function($query) use ($fromUserId, $toUserId) {
                $query->where('from_id', $fromUserId)
                    ->where('to_id', $toUserId);
            })
            ->orWhere(function($query) use ($fromUserId, $toUserId) {
                $query->where('to_id', $fromUserId)
                    ->where('from_id', $toUserId);
            }) ->orderBy('created_at', 'desc')
            ->paginate($limit);


        $paginationInfo = getPaginationInfo($chats, $limit);
        return $this->success(message: 'successfully', data: [
            'messages' => ConnectionChatResource::collection($chats),
            'pagination' => $paginationInfo
        ]);

    }

    //Matchmaker Chat list
    public function matchmakerChatsUserList(){
        $userIds = ConnectionToMatchmaker::query()->get();
        return $this->success(
            message: 'Successfully',
            data: getMatchmakerConnectionResource::collection($userIds),
        );
    }

    public function matchmakerChatList(getMatchmakerChatRequest $request){

        $limit = (int) $request->input('limit', 20);

        $fromUserId = $request->matchmaker_id;
        $toUserId = $request->user_id;

        $chats = MatchMakerChat::query()
            ->where(function($query) use ($fromUserId, $toUserId) {
                $query->where('from_id', $fromUserId)
                    ->where('to_id', $toUserId);
            })
            ->orWhere(function($query) use ($fromUserId, $toUserId) {
                $query->where('to_id', $fromUserId)
                    ->where('from_id', $toUserId);
            }) ->orderBy('created_at', 'desc')
            ->paginate($limit);


        $paginationInfo = getPaginationInfo($chats, $limit);
        return $this->success(message: 'successfully', data: [
            'messages' => matchmakerChatResource::collection($chats),
            'pagination' => $paginationInfo
        ]);
    }

    public function mentorChatsUserList(){
        $userIds = ConnectionToMentor::query()->get();
        return $this->success(
            message: 'Successfully',
            data: getMentorConnectionResource::collection($userIds),
        );
    }

    public function mentorChatList(getMentorChatRequest $request){

        $limit = (int) $request->input('limit', 20);

        $fromUserId = $request->mentor_id;
        $toUserId = $request->user_id;

        $chats = MentorChat::query()
            ->where(function($query) use ($fromUserId, $toUserId) {
                $query->where('from_id', $fromUserId)
                    ->where('to_id', $toUserId);
            })
            ->orWhere(function($query) use ($fromUserId, $toUserId) {
                $query->where('to_id', $fromUserId)
                    ->where('from_id', $toUserId);
            }) ->orderBy('created_at', 'desc')
            ->paginate($limit);


        $paginationInfo = getPaginationInfo($chats, $limit);
        return $this->success(message: 'successfully', data: [
            'messages' => mentorChatResource::collection($chats),
            'pagination' => $paginationInfo
        ]);
    }
}
