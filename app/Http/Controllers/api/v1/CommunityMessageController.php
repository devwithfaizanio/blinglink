<?php

namespace App\Http\Controllers\api\v1;

use App\Events\CommunityMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\community\sndMsgRequest;
use App\Http\Resources\api\v1\community\msgResource;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\CommunityMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommunityMessageController extends Controller
{
    //
    // Get all messages for a community
    public function index(Request $request,$communityId)
    {
        $validateUser = \Validator::make($request->all(),
            [
                'limit' => 'nullable|integer',
                'page' => 'nullable|integer'
            ]);

        if($validateUser->fails()){
            return $this->error(message: $validateUser->messages()->first(),code: 422);
        }
        $limit = (int) $request->input('limit', 10);
        $community = Community::find($communityId);
        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }
        // Check if user is member or creator
        if (!$community->isMember(Auth::id()) && !$community->isCreator(Auth::id())) {
            return $this->forbidden(message: 'You are not a member of this community');
        }
        $messages = CommunityMessage::query()->where('community_id', $communityId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        $paginationInfo = getPaginationInfo($messages, $limit);
        return $this->success(message: 'successfully', data: [
            'messages' => msgResource::collection($messages),
            'pagination' => $paginationInfo
        ]);
    }

    // Send a message (only creator can send)
    public function store(sndMsgRequest $request)
    {
        $communityId = $request->community_id;
        $community = Community::find($communityId);

        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }

        // Check if user is creator
        if (!$community->isCreator(Auth::id())) {
            return $this->forbidden(message: 'Only the creator can send messages in this community');
        }
        if ($request->hasFile('attachment')) {
            $attachment = ImageService::addImage('images/communities/message', $request->file('attachment'), 'message_');
        }

        $data = [
            'community_id' => $communityId,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment' => $attachment ?? null
        ];

        $message = CommunityMessage::create($data);

        broadcast(new CommunityMessageSent($message))->toOthers();

        return $this->success(
            message: 'Message sent successfully',
//            data: CommunityMessageResource::make($message->load('user'))
        );
    }

    // Delete a message (only creator can delete their own messages)
    public function destroy($communityId, $messageId)
    {
        $community = Community::find($communityId);

        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }

        $message = CommunityMessage::where('community_id', $communityId)
            ->where('id', $messageId)
            ->first();

        if (!$message) {
            return $this->notFound(message: 'Message not found');
        }

        if (!$community->isCreator(Auth::id())) {
            return $this->forbidden(message: 'Only the creator can delete messages in this community');
        }

        // Delete attachment if exists
        if ($message->attachment && file_exists(public_path($message->attachment))) {
            unlink(public_path($message->attachment));
        }

        $message->delete();

        return $this->success(message: 'Message deleted successfully');
    }

}
