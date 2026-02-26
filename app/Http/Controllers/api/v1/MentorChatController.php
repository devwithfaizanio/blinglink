<?php

namespace App\Http\Controllers\api\v1;

use App\Events\MentorMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\connectionChat\sndMsgRequest;
use App\Http\Resources\api\v1\mentorChat\ChatResource;
use App\Http\Resources\api\v1\mentorChat\ChatUserListResource;
use App\Models\ConnectionToMentor;
use App\Models\MentorChat;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;

class MentorChatController extends Controller
{
    //
    public function ListUserChat(){
        $authUserId = request()->user()->id;
        $chats = MentorChat::query()->where('from_id', $authUserId)
            ->orWhere('to_id', $authUserId)
            ->select('from_id', 'to_id')
            ->get();

        $userIds = $chats->pluck('from_id')->merge($chats->pluck('to_id'))->unique();
        $userIds = $userIds->reject(function ($userId) use ($authUserId) {
            return $userId == $authUserId;
        });
        $users = User::query()->whereIn('id', $userIds)->get();
        return $this->success(message: 'Successfully' , data: ChatUserListResource::collection($users));
    }

    public function ChatList(Request $request, $id){

        $validateUser = \Validator::make($request->all(),
            [
                'limit' => 'nullable|integer',
                'page' => 'nullable|integer'
            ]);

        if($validateUser->fails()) {
            return $this->error(message: $validateUser->messages()->first(), code: 422);
        }
        $limit = (int) $request->input('limit', 20);



        $authUserId = request()->user()->id;

        $chats = MentorChat::query()
            ->where(function($query) use ($authUserId, $id) {
                $query->where('from_id', $authUserId)
                    ->where('to_id', $id);
            })
            ->orWhere(function($query) use ($authUserId, $id) {
                $query->where('to_id', $authUserId)
                    ->where('from_id', $id);
            }) ->orderBy('created_at', 'desc')
            ->paginate($limit);


        $paginationInfo = getPaginationInfo($chats, $limit);
        return $this->success(message: 'successfully', data: [
            'messages' => ChatResource::collection($chats),
            'pagination' => $paginationInfo
        ]);

    }

    public function SendMessage(sndMsgRequest $request)
    {
        $connectionExists = ConnectionToMentor::query()
            ->where(function ($query) use ($request) {
                $query->where('user_id', request()->user()->id)
                    ->where('mentor_id', $request->to_id);
            })
            ->orWhere(function ($query) use ($request) {
                $query->where('user_id', $request->to_id)
                    ->where('mentor_id', request()->user()->id);
            })
            ->exists();

        if (!$connectionExists) {
            return $this->forbidden(message: 'You can only send messages to your mentor connections');
        }

        if ($request->hasFile('attachment')) {
            $attachment = ImageService::addImage(
                'images/mentor-chat/message',
                $request->file('attachment'),
                'message_'
            );
        }

        $message = new MentorChat();
        $message->from_id = request()->user()->id;
        $message->to_id = $request->to_id;
        $message->message = $request->message;
        $message->attachment = $attachment ?? null;
        $message->save();

        // Broadcast like Pusher
        broadcast(new MentorMessageSent($message))->toOthers();

        return $this->success(message: 'Successfully');
    }
}
