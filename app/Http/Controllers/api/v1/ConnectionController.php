<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\connections\acceptConnectionRequest;
use App\Http\Requests\api\v1\connections\sendConnectionRequest;
use App\Http\Resources\api\v1\auth\UserListResource;
use App\Http\Resources\api\v1\connections\connectionResource;
use App\Http\Resources\api\v1\connections\connectionSuggestionResource;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
//    public function suggestedConnections()
//    {
//        $authUser = auth()->user();
//
//        $allowedRoles = ['user', 'matchmaker', 'mentor'];
//
//        // 1) Pending requests received by me (should be on top)
//        $pendingRequests = Connection::query()
//            ->where('requested_id', $authUser->id)
//            ->where('status', 'pending')
//            ->pluck('requester_id')
//            ->toArray();
//
//        // 2) Exclude accepted + blocked + pending already sent by me
//        $excludedUserIds = Connection::query()
//            ->where(function ($q) use ($authUser) {
//                $q->where('requester_id', $authUser->id)
//                    ->orWhere('requested_id', $authUser->id);
//            })
//            ->whereIn('status', ['accepted', 'blocked'])
//            ->get()
//            ->map(function ($connection) use ($authUser) {
//                return $connection->requester_id == $authUser->id
//                    ? $connection->requested_id
//                    : $connection->requester_id;
//            })
//            ->unique()
//            ->values()
//            ->toArray();
//
//        // Exclude yourself
//        $excludedUserIds[] = $authUser->id;
//
//        // -----------------------------------
//        // A) USERS WHO SENT ME PENDING REQUEST
//        // -----------------------------------
//        $pendingUsers = User::query()
//            ->whereIn('id', $pendingRequests)
//            ->whereIn('role', $allowedRoles)
//            ->select([
//                'id',
//                'f_name',
//                'role',
//                'age',
//                'gender',
//                'nationality',
//                'profession',
//                'dubai_location',
//                'education_level',
//                'family',
//                'your_interest',
//                'lifestyle_preference',
//                'bio',
//            ])
//            ->selectRaw("1000 as match_score") // always top
//            ->get();
//
//        // -----------------------------------
//        // B) NORMAL SUGGESTED USERS
//        // -----------------------------------
//        $authInterests = json_encode($authUser->your_interest ?? []);
//        $authLifestyle = json_encode($authUser->lifestyle_preference ?? []);
//
//        $suggestedUsers = User::query()
//            ->whereIn('role', $allowedRoles)
//            ->whereNotIn('id', array_merge($excludedUserIds, $pendingRequests))
//            ->select([
//                'id',
//                'f_name',
//                'role',
//                'age',
//                'gender',
//                'nationality',
//                'profession',
//                'dubai_location',
//                'education_level',
//                'family',
//                'your_interest',
//                'lifestyle_preference',
//                'bio',
//            ])
//            ->selectRaw("
//            (
//                (CASE WHEN dubai_location = ? THEN 30 ELSE 0 END) +
//                (CASE WHEN nationality = ? THEN 15 ELSE 0 END) +
//                (CASE WHEN education_level = ? THEN 10 ELSE 0 END) +
//                (CASE WHEN profession = ? THEN 10 ELSE 0 END) +
//                (CASE WHEN family = ? THEN 8 ELSE 0 END) +
//                (CASE WHEN ABS(age - ?) <= 3 THEN 10 ELSE 0 END) +
//                (CASE WHEN JSON_OVERLAPS(your_interest, ?) THEN 20 ELSE 0 END) +
//                (CASE WHEN JSON_OVERLAPS(lifestyle_preference, ?) THEN 15 ELSE 0 END)
//            ) as match_score
//        ", [
//                $authUser->dubai_location,
//                $authUser->nationality,
//                $authUser->education_level,
//                $authUser->profession,
//                $authUser->family,
//                $authUser->age,
//                $authInterests,
//                $authLifestyle
//            ])
//            ->orderByDesc('match_score')
//            ->limit(50)
//            ->get();
//
//        // -----------------------------------
//        // FINAL RESULT (Pending first)
//        // -----------------------------------
//        $final = $pendingUsers->merge($suggestedUsers);
//
//        return $this->success(message: 'Suggested users', data: $final);
//    }

    public function suggestedConnections(Request $request)
    {
        $authUser = auth()->user();
        $limit = $request->get('limit', 20);

        $allowedRoles = ['user', 'matchmaker', 'mentor'];

        /**
         * 1) Pending requests received by me
         * We need connection_id so we will fetch connections, not only user ids
         */
        $pendingConnections = Connection::query()
            ->where('requested_id', $authUser->id)
            ->where('status', 'pending')
            ->with(['requester:id,f_name,email,role,age,gender,nationality,profession,company,dubai_location,height,education_level,family,lifestyle_preference,bio,your_interest,languages,linkedin_profile'])
            ->latest()
            ->get();

        // requester ids to exclude from suggestions list
        $pendingRequesterIds = $pendingConnections->pluck('requester_id')->toArray();

        /**
         * 2) Exclude accepted + blocked connections
         */
        $excludedUserIds = Connection::query()
            ->where(function ($q) use ($authUser) {
                $q->where('requester_id', $authUser->id)
                    ->orWhere('requested_id', $authUser->id);
            })
            ->whereIn('status', ['accepted', 'blocked'])
            ->get()
            ->map(function ($connection) use ($authUser) {
                return $connection->requester_id == $authUser->id
                    ? $connection->requested_id
                    : $connection->requester_id;
            })
            ->unique()
            ->values()
            ->toArray();

        // Always exclude yourself
        $excludedUserIds[] = $authUser->id;

        $authInterests = json_encode($authUser->your_interest ?? []);
        $authLifestyle = json_encode($authUser->lifestyle_preference ?? []);

        /**
         * 3) Suggested users (paginated)
         */
        $users = User::query()
            ->whereIn('role', $allowedRoles)
            ->whereNotIn('id', array_merge($excludedUserIds, $pendingRequesterIds))

            ->select([
                'id',
                'f_name',
                'email',
                'role',
                'age',
                'gender',
                'nationality',
                'profession',
                'company',
                'dubai_location',
                'height',
                'education_level',
                'family',
                'lifestyle_preference',
                'bio',
                'your_interest',
                'languages',
                'linkedin_profile',
                'profile_image'
            ])

            ->selectRaw("
            (
                (CASE WHEN dubai_location = ? THEN 30 ELSE 0 END) +
                (CASE WHEN nationality = ? THEN 15 ELSE 0 END) +
                (CASE WHEN education_level = ? THEN 10 ELSE 0 END) +
                (CASE WHEN profession = ? THEN 10 ELSE 0 END) +
                (CASE WHEN company = ? THEN 6 ELSE 0 END) +
                (CASE WHEN family = ? THEN 8 ELSE 0 END) +
                (CASE WHEN ABS(age - ?) <= 3 THEN 10 ELSE 0 END) +
                (CASE WHEN JSON_OVERLAPS(your_interest, ?) THEN 20 ELSE 0 END) +
                (CASE WHEN JSON_OVERLAPS(lifestyle_preference, ?) THEN 15 ELSE 0 END)
            ) as match_score
        ", [
                $authUser->dubai_location,
                $authUser->nationality,
                $authUser->education_level,
                $authUser->profession,
                $authUser->company,
                $authUser->family,
                $authUser->age,
                $authInterests,
                $authLifestyle
            ])

            ->orderByDesc('match_score')
            ->paginate($limit);

        $paginationInfo = getPaginationInfo($users, $limit);


        return $this->success(message: 'successfully', data: [
            'pending_requests' => connectionResource::collection($pendingConnections),
            'users' => connectionSuggestionResource::collection($users),
            'pagination' => $paginationInfo,
        ]);
    }




    public function myConnections()
    {
        $user = auth()->user();

        $connections = Connection::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
                ->orWhere('requested_id', $user->id);
        })
            ->where('status', 'accepted')
            ->latest()
            ->get();

        return $this->success(message: 'My connections retrieved successfully', data: connectionResource::collection($connections));
    }
    public function send(sendConnectionRequest $request)
    {
        $user = auth()->user();

        if ($user->id == $request->requested_id) {
            $this->forbidden(message: 'You cannot connect with yourself');
        }


        $existingConnection = Connection::where(function ($q) use ($user, $request) {
            $q->where('requester_id', $user->id)
                ->where('requested_id', $request->requested_id);
        })->orWhere(function ($q) use ($user, $request) {
            $q->where('requester_id', $request->requested_id)
                ->where('requested_id', $user->id);
        })->first();



        if ($existingConnection) {
            if ($existingConnection->status == 'pending') {
                return $this->error(message: 'Connection request already sent but pending');
            } elseif ($existingConnection->status == 'accepted') {
                return $this->error(message: 'You are already connected');
            } elseif ($existingConnection->status == 'rejected') {
                $existingConnection->update([
                    'status' => 'pending',
                    'requester_id' => $user->id,
                    'requested_id' => $request->requested_id,
                ]);
                return $this->success(message: 'Connection request sent again successfully');
            }
        }

         Connection::create([
            'requester_id' => $user->id,
            'requested_id' => $request->requested_id,
            'status' => 'pending',
        ]);

        return $this->success(message: 'Connection sent successfully');
    }
    public function accept(AcceptConnectionRequest $request)
    {
        $user = auth()->user();

        $connection = Connection::where('id', $request->connection_id)
            ->where('requested_id', $user->id)
            ->first();
        if (!$connection) {
            return $this->error(message: 'You cannot accept this connection request.');
        }

        if ($connection->status === 'accepted') {
            return $this->error(message: 'Connection already accepted.');
        }

        if ($connection->status !== 'pending') {
            return $this->error(message: 'Connection request is not pending.');
        }

        $connection->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return $this->success(message: 'Connection accepted successfully.');
    }

    public function reject(acceptConnectionRequest $request)
    {

        $user = auth()->user();

        $connection = Connection::where('id', $request->connection_id)
            ->where('requested_id', $user->id)
            ->first();
        if (!$connection) {
            return $this->forbidden(message: 'You cannot accept this connection request. you are not the requested user.');
        }
        $connection->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return $this->success(message: 'Connection rejected successfully');
    }
    //cancel mean delete the request if it's pending or delete the connection if it's accepted
    public function cancel(acceptConnectionRequest $request)
    {
        $user = auth()->user();

        $connection = Connection::where('id', $request->connection_id)->where('status', 'pending')
            ->where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                    ->orWhere('requested_id', $user->id);
            })
            ->first();
        if (!$connection) {
            return $this->forbidden(message: 'You cannot cancel this connection request. you are not part of this connection.');
        }
        $connection->delete();

        return $this->success(message: 'Connection cancelled successfully');
    }




}
