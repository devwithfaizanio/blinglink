<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\accountStatusRequest;
use App\Http\Requests\api\v1\admin\getUserRequest;
use App\Http\Resources\api\v1\admin\getMatchmakerResource;
use App\Http\Resources\api\v1\admin\getUserResource;
use App\Models\Connection;
use App\Models\ConnectionChat;
use App\Models\ConnectionToMatchmaker;
use App\Models\ConnectionToMentor;
use App\Models\MatchMakerChat;
use App\Models\MentorChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin · Users', weight: 0)]
class UserController extends Controller
{

    //allUsers
    public function allUsers(getUserRequest $request)
    {

        $limit = (int) $request->input('limit', 20);

        $users = User::query()
            ->where('role', '!=', 'admin')

            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })

            ->when($request->has('verified'), function ($query) use ($request) {
                $query->where('is_approved', $request->verified);
            })
            ->when($request->has('is_trophy'), function ($query) use ($request) {
                $query->where('trophy_id', $request->is_trophy);
            })

            ->latest()
//            ->get();


        ->paginate($limit);


        $paginationInfo = getPaginationInfo($users, $limit);

        return $this->success(message: 'successfully', data: [
            'users' => getUserResource::collection($users),
            'pagination' => $paginationInfo
        ]);

    }

    //allUsers
    public function singleUser(Request $request,$userId)
    {
        $users = User::query()->where('id', $userId)->first();
        if(!$users){
            return $this->notFound(message:  'User not found');
        }

        return $this->success(message:  'Users fetched successfully',data:  getUserResource::make($users) );
    }
    //verifyUser

    public function verifyUser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'verify' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return $this->notFound(message: 'User not found');
        }
        $user->is_approved = $request->verify;
        $user->save();

        return $this->success(
            message: $request->verify == 1 ? 'User verified successfully' : 'User unverified successfully',
        );
    }

    //getMatchmakers
    public function getMatchmakers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'limit' => 'nullable',
            'page' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $limit  = $request->limit ?? 10;
        $matchmakers = ConnectionToMatchmaker::query()
            ->latest()
            ->paginate($limit);

        $paginationInfo = getPaginationInfo($matchmakers, $limit);


        return $this->success(
            message: 'matchmakers retrieved successfully.',
            data: [
                'matchmaker' => getMatchmakerResource::collection($matchmakers),
                'pagination' => $paginationInfo
            ]
        );
    }

    //accountStatusUpdate
    public function accountStatusUpdate(accountStatusRequest $request){

        $user = User::find($request->user_id);
        $user->account_status = $request->status;
        $user->save();

        $messages = [
            'normal'    => 'User account normal successfully',
            'warn'      => 'User account warned successfully',
            'suspended' => 'User account suspended successfully',
            'ban'       => 'User account banned successfully',
        ];

        return $this->success(
            message: $messages[$request->status] ?? 'User status updated successfully',
        );
    }

    //netWork
    public function netWork(Request $request)
    {
        $userConnectionCount = Connection::query()->where('status', 'accepted')->count();
        $mentorConnectionCount = ConnectionToMentor::query()->count();
        $matchmakerConnectionCount = ConnectionToMentor::query()->count();

        $connectionChatCount = ConnectionChat::query()->count();
        $mentorChatCount = MentorChat::query()->count();
        $matchmakerChatCount = MatchMakerChat::query()->count();

        $totalConnections = $userConnectionCount + $mentorConnectionCount + $matchmakerConnectionCount;
        $totalChats = $connectionChatCount + $mentorChatCount + $matchmakerChatCount;

        return $this->success(
            message: 'successfully',
            data: [
                'userConnectionCount' => $userConnectionCount,
                'mentorConnectionCount' => $mentorConnectionCount,
                'matchmakerConnectionCount' => $matchmakerConnectionCount,
                'totalConnections' => $totalConnections,

                'connectionChatCount' => $connectionChatCount,
                'mentorChatCount' => $mentorChatCount,
                'matchmakerChatCount' => $matchmakerChatCount,
                'totalChats' => $totalChats,
            ]
        );
    }


}
