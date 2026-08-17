<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\community\acceptRejectRequest;
use App\Http\Requests\api\v1\community\addCommunityMemberRequest;
use App\Http\Requests\api\v1\community\createCommunityRequest;
use App\Http\Requests\api\v1\community\getCommunitiesRequest;
use App\Http\Resources\api\v1\community\getAllCommunities;
use App\Http\Resources\api\v1\community\getMemberListResource;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    // Get all communities for authenticated user
//    public function index()
//    {
//        $user = Auth::user();
//
//        $communities = Community::where('creator_id', $user->id)
//            ->orWhereHas('members', function($query) use ($user) {
//                $query->where('user_id', $user->id);
//            })
//            ->with(['creator', 'members'])
//            ->latest()
//            ->get();
//        $showMembers = false;
//
//        return $this->success(message: 'All Communities', data: getAllCommunities::collection(
//                $communities->values()
//            )->map(fn($item) => new getAllCommunities($item, $showMembers)),
//        );
//
//    }

    public function index(getCommunitiesRequest $request)
    {
        $limit = $request->limit ?? 10;
        $isMine = $request->boolean('isMine', false);
        $status = $request->status ?? 'all';

        if ($isMine) {
            // Only communities created by the authenticated user
//            $query = Community::where('creator_id', auth()->id());
            $user = Auth::user();
            $query = Community::where('creator_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        } else {
            // All communities
            $query = Community::query();
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $communities = $query->with(['creator', 'members'])
            ->latest()
            ->paginate($limit);

        $paginationInfo = getPaginationInfo($communities, $limit);
        $showMembers = false;

        return $this->success(
            message: 'All Communities',
            data: [
                'communities' => getAllCommunities::collection($communities)
                    ->map(fn ($item) => new getAllCommunities($item, $showMembers)),
                'pagination' => $paginationInfo,
            ]
        );
    }

    // Create a new community
    public function store(createCommunityRequest $request)
    {

        $data = $request->only(['name', 'description']);
        $data['creator_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = ImageService::addImage('images/communities', $request->file('image'), 'community_');
        }

        $community = Community::create($data);

        // Automatically add creator as a member
        $community->members()->attach(Auth::id(), ['joined_at' => now()]);

        $showMembers = false;

        return $this->success(message: 'Community created successfully', data: new getAllCommunities($community, $showMembers));
    }

    // Get single community details
    public function show($communityId)
    {
        $community = Community::with(['creator', 'members', 'messages.user'])->find($communityId);

        if (!$community) {
            return $this->notFound( message: 'Community not found');
        }

        // Check if user is member or creator
        if (!$community->isMember(Auth::id()) && !$community->isCreator(Auth::id())) {
            return $this->forbidden( message: 'You are not a member of this community');
        }
        $showMembers = false;
        return $this->success(message: 'Single Communities', data: new getAllCommunities($community, $showMembers));
    }

    // Update community (only creator can update)
    public function update(Request $request, $id)
    {
        $community = Community::find($id);

        if (!$community) {
            return response()->json([
                'success' => false,
                'message' => 'Community not found'
            ], 404);
        }

        // Check if user is creator
        if (!$community->isCreator(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Only the creator can update the community'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['name', 'description', 'is_active']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($community->image && file_exists(public_path($community->image))) {
                unlink(public_path($community->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('communities'), $imageName);
            $data['image'] = 'communities/' . $imageName;
        }

        $community->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Community updated successfully',
            'data' => $community->load(['creator', 'members'])
        ], 200);
    }

    // Delete community (only creator can delete)
    public function destroy($id)
    {
        $community = Community::find($id);
        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }
        if (!$community->isCreator(auth()->id())) {
            return $this->forbidden(message: 'Only the creator can delete this community');
        }
        // Delete associated image if exists
        if ($community->image) {
            ImageService::deleteImage('images/communities', $community->image);
        }

        $community->delete();

        return $this->success(message: 'Community deleted successfully');
    }

    // Add member to community (only creator can add)
    public function addMember(addCommunityMemberRequest $request)
    {
        $communityId = $request->community_id;
        $community = Community::find($communityId);
        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }
        if (!$community->isCreator(auth()->id())) {
            return $this->forbidden(message: 'Only the creator can add members');
        }


        // Convert comma-separated string to array and remove empty values
        $userIds = array_filter(array_map('trim', explode(',', $request->user_ids)));

        $userIds = array_unique($userIds);

        $existingUsers = \App\Models\User::whereIn('id', $userIds)
            ->select('id', 'f_name', 'email')
            ->get();

        $existingUserIds = $existingUsers->pluck('id')->toArray();
        $invalidUsers = array_diff($userIds, $existingUserIds);

        if (!empty($invalidUsers)) {
            $invalidIds = implode(', ', array_values($invalidUsers));
            $validIds = implode(', ', $existingUserIds);
            return $this->badRequest(message: "Some user IDs do not exist. Invalid user IDs: ({$invalidIds}). Valid user IDs: ({$validIds})");
        }

        $addedCount = 0;
        $alreadyMemberCount = 0;

        foreach ($userIds as $userId) {
            // Check if user is already a member
            if ($community->isMember($userId)) {
                $alreadyMemberCount++;
            } else {
                $community->members()->attach($userId, ['joined_at' => now()]);
                $addedCount++;
            }
        }

        if ($addedCount === 0) {
            return $this->badRequest(message: 'All users are already members of this community');
        }

        return $this->success(message: $addedCount === count($userIds)
            ? 'All members added successfully'
            : "{$addedCount} member(s) added successfully, {$alreadyMemberCount} were already members");

    }

    // Remove member from community (only creator can remove)
    public function removeMember(addCommunityMemberRequest $request)
    {

        $communityId = $request->community_id;
        $community = Community::find($communityId);
        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }
        if (!$community->isCreator(auth()->id())) {
            return $this->forbidden(message: 'Only the creator can remove members');
        }

        // Convert comma-separated string to array and remove empty values
        $userIds = array_filter(array_map('trim', explode(',', $request->user_ids)));

        // Remove duplicates
        $userIds = array_unique($userIds);

        // Validate that all IDs exist in users table
        $existingUsers = \App\Models\User::whereIn('id', $userIds)
            ->select('id', 'f_name', 'email')
            ->get();

        $existingUserIds = $existingUsers->pluck('id')->toArray();
        $invalidUsers = array_diff($userIds, $existingUserIds);

        if (!empty($invalidUsers)) {
            $invalidIds = implode(', ', array_values($invalidUsers));
            $validIds = implode(', ', $existingUserIds);
            return $this->badRequest(message: "Some user IDs do not exist. Invalid user IDs: ({$invalidIds}). Valid user IDs: ({$validIds})");
        }

        // Check if creator is in the list
        if (in_array($community->creator_id, $userIds)) {
            return $this->badRequest(message: 'Creator cannot be removed from the community');
        }

        $removedCount = 0;
        $notMemberCount = 0;

        foreach ($userIds as $userId) {
            // Check if user is a member
            if ($community->isMember($userId)) {
                $community->members()->detach($userId);
                $removedCount++;
            } else {
                $notMemberCount++;
            }
        }

        if ($removedCount === 0) {
            return $this->badRequest(message: 'None of the users are members of this community');
        }

        return $this->success(
            message: $removedCount === count($userIds)
                ? 'Members removed successfully'
                : "{$removedCount} member(s) removed successfully, {$notMemberCount} were not members",
        );
    }

    // Get community members
    public function getMembers($id)
    {
        $community = Community::find($id);

        if (!$community) {
            return $this->notFound(message: 'Community not found');
        }

        // Check if user is member or creator
        if (!$community->isMember(Auth::id()) && !$community->isCreator(Auth::id())) {
            return $this->forbidden(message: 'You are not a member of this community');
        }

        return $this->success(message: 'success', data: getMemberListResource::make($community->load('members')));
    }



    public function acceptDecline(acceptRejectRequest $request)
    {
        $community = Community::findOrFail($request->community_id);

        $community->update([
            'status' => $request->status,
        ]);

        $message = $request->status === 'accepted'
            ? 'Accepted successfully.'
            : 'Declined successfully.';



        return $this->success(message: $message, data: null);

    }


}
