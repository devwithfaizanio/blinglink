<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\CheckUserAvailabilityRequest;
use App\Http\Requests\api\v1\auth\getUsersRequest;
use App\Http\Requests\api\v1\auth\LoginRequest;
use App\Http\Requests\api\v1\auth\matchMakerProfileRequest;
use App\Http\Requests\api\v1\auth\mentorProfileRequest;
use App\Http\Requests\api\v1\auth\RegisterRequest;
use App\Http\Requests\api\v1\auth\switchRoleRequest;
use App\Http\Requests\api\v1\auth\updateProfileRequest;
use App\Http\Resources\api\v1\auth\profileResource;
use App\Http\Resources\api\v1\auth\UserListResource;
use App\Models\Connection;
use App\Models\MatchmakerProfiles;
use App\Models\MentorProfile;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\UserReferral;
use App\Notifications\api\v1\FriendJoinedViaReferralNotification;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function CheckUserAvailability(CheckUserAvailabilityRequest $request)
    {
        try {
            return $this->success(message: 'User is available', data: null);
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(),code: (int)$th->getCode());
        }
    }
    //register
    public function register(RegisterRequest $request)
    {
        try {
            $lifestylePreference = array_map(
                'trim',
                explode(',', $request->lifestyle_preference)
            );

            $yourInterest = array_map(
                'trim',
                explode(',', $request->your_interest)
            );

            if ($request->hasFile('emirate_id')) {
                $emirateId = ImageService::addImage('images/user/emirate_id', $request->file('emirate_id'), 'emirate_');
            }
            if ($request->hasFile('profile_image')) {
                $profileImage = ImageService::addImage('images/user/profile_image', $request->file('profile_image'), 'profile_image_');
            }
            $promoCode = null;
            if ($request->filled('promo_code')) {
                $promoCode = PromoCode::where('code', strtoupper($request->promo_code))->first();

                if (!$promoCode) {
                    return $this->error(message: 'Invalid promo code', code: 400);
                }

                if ($promoCode->status !== 'active') {
                    return $this->error(message: 'This promo code is inactive', code: 400);
                }

                if ($promoCode->start_date && $promoCode->start_date > now()) {
                    return $this->error(message: 'This promo code is not active yet', code: 400);
                }

                if ($promoCode->expires_at && $promoCode->expires_at < now()) {
                    return $this->error(message: 'This promo code has expired', code: 400);
                }

                if ($promoCode->max_uses !== null && $promoCode->uses_count >= $promoCode->max_uses) {
                    return $this->error(message: 'This promo code usage limit has been reached', code: 400);
                }
            }

            $referrer = null;
            if ($request->filled('referral_code')) {
                $referrer = User::where('referral_code', strtoupper($request->referral_code))->first();
            }
//            else {
//                // Auto-match referral by invited friend email if code was not typed
//                $pendingInvite = UserReferral::where('friend_email', $request->email)
//                    ->where('status', 'invited')
//                    ->latest()
//                    ->first();
//                if ($pendingInvite) {
//                    $referrer = User::find($pendingInvite->referrer_id);
//                }
//            }

            // Create User
            $user = User::create([
                'f_name' => $request->f_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'fcm_token' => $request->fcm_token,
                'profile_image' => $profileImage,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'profession' => $request->profession,
                'company' => $request->company,
                'dubai_location' => $request->dubai_location,
                'height' => $request->height,
                'education_level' => $request->education_level,
                'family' => $request->family,

                // JSON fields
                'lifestyle_preference' => $lifestylePreference,
                'your_interest' => $yourInterest,


                'bio' => $request->bio,
                'linkedin_profile' => $request->linkedin_profile,
                'emirate_id' => $emirateId,
                'promo_code_id' => $promoCode?->id,
                'subscription_plan' => $promoCode ? 'vip' : 'free',
                'referred_by_id' => $referrer?->id,
                'is_approved' => false,
            ]);

            if ($promoCode) {
                $promoCode->increment('uses_count');
            }

            if ($referrer) {
                // Auto-create connection between referrer and newly registered friend
                Connection::create([
                    'requester_id' => $referrer->id,
                    'requested_id' => $user->id,
                    'status' => 'accepted',
                ]);

                // Update invitation tracking
                UserReferral::where('referrer_id', $referrer->id)
                    ->where('friend_email', $user->email)
                    ->update([
                        'status' => 'registered',
                        'registered_user_id' => $user->id,
                    ]);

                // Notify referrer
//                try {
//                    $referrer->notify(new FriendJoinedViaReferralNotification($user));
//                } catch (\Throwable $e) {
//                    // Ignore notification delivery failure during registration
//                }
            }

            return $this->forbidden(
                message: 'User registered successfully',
                data: [
                    'is_approved' => $user->is_approved ? true : false,
                ]
            );

        } catch (\Throwable $th) {
            return $this->error(
                message: $th->getMessage(),
            );
        }
    }
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::query()->where('email', $request->email)->first();


            if (!Hash::check($request->password, $user->password)) {
                return $this->forbidden(message: 'Incorrect password', code: 403);
            }



            if (!$user->is_approved && $user->role != 'admin') {
                return $this->forbidden( message: 'Your account is not approved yet',
                    data: [
                        'is_approved' => false
                    ]
                );
            }


            auth()->login($user);
            $user = auth()->user();

            $user->update(['fcm_token' => $request->input('fcm_token')]);

            return $this->success(
                message: 'Logged in successfully',
                data: [
                    'token' => $user->createToken('API TOKEN')->plainTextToken,
                    'is_approved' => true,
                    'role' => $user->role,
                ]
            );
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(), code: $th->getCode());
        }
    }
    //switchRole
    public function switchRole(switchRoleRequest $request)
    {
        try {
            $authUser = auth()->user();
            $user = User::query()->where('id', $authUser->id)->first();
            if (!$user) {
                return $this->notFound(message: 'User not found');
            }
            $user->role = $request->role;
            if($request->role == 'matchmaker'){
                MatchmakerProfiles::firstOrCreate(
                    ['user_id' => $user->id],
                );
            }
            if($request->role == 'mentor'){
                MentorProfile::firstOrCreate(
                    ['user_id' => $user->id],
                );
            }
            $user->save();

            return $this->success(message: 'User role switched successfully', data: null);
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
        }

    }
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success( message: 'Logged out successfully');
    }

    public function deleteUser(Request $request)
    {
        $user = $request->user();

        // Optional: revoke all tokens for this user before deleting
//        $user->tokens()->delete();

        $user->delete();

        return $this->success(message: 'Account deleted successfully');
    }




    //getUserList
    public function getUserList(getUsersRequest $request)
    {
        try {
            $role = $request->role;

            $users = User::query()->where('id', '!=', auth()->id())->where('is_approved', true)
                ->when($role !== 'all', function ($query) use ($role) {
                $query->where('role', $role);
            })
                ->when($role === 'all', function ($query) {
                    $query->where('role', '!=', 'admin');
                })
                ->get();

            return $this->success(
                message: 'User list fetched successfully',
                data: UserListResource::collection($users),
            );
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
        }
    }

    //getUserStatusByRole
    public function getUserStatusByRole(Request $request)
    {
        try {
            $authUser = auth()->user();
            $user = User::query()->where('id', $authUser->id)->first();
            if (!$user) {
                return $this->error(message: 'User not found', code: 404);
            }

            $data = [
                'role' => $user->role,
                'is_approved' => (bool) $user->is_approved,
                'hasMatchmakerProfile' => $user->matchmakerProfile ? true : false,
                'hasMentorProfile' => $user->mentorProfile ? true : false,
            ];

            if ($user->role === 'matchmaker') {
                $data['matchmakerApprovalStatusByAdmin'] = (bool) MatchmakerProfiles::where('user_id', $user->id)
                    ->value('is_approved');
            }
            if ($user->role === 'mentor') {
                $data['mentorApprovalStatusByAdmin'] = (bool) MentorProfile::where('user_id', $user->id)
                    ->value('is_approved');
            }

            return $this->success(
                message: 'User status fetched successfully',
                data: $data
            );

        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
        }
    }

    //storeMatchmakerProfile
    public function storeMatchmakerProfile(matchMakerProfileRequest $request)
    {
        try {
            $authUser = auth()->user();

            $matchMakerProfile = MatchmakerProfiles::query()->where('user_id', $authUser->id)->first();
            if (!$matchMakerProfile) {
                return $this->notFound(message: 'not found');
            }

            if($matchMakerProfile->id_document == null){
                if ($request->hasFile('id_document')) {
                    $documentPath = ImageService::addImage('images/matchprofiles', $request->file('id_document'), 'matchmaker_');
                }
            }else{
                if ($request->hasFile('id_document')) {
                    $documentPath = ImageService::updateImage('images/matchprofiles/',$request->id_document, $matchMakerProfile->id_document, 'matchmaker_');
                }
            }

            $matchmakingType = array_map(
                'trim',
                explode(',', $request->matchmaking_type)
            );

            $coverageArea = array_map(
                'trim',
                explode(',', $request->coverage_area)
            );

            $matchMakerProfile->phone = $request->phone;
            $matchMakerProfile->city = $request->city;
            $matchMakerProfile->price = $request->price;
            $matchMakerProfile->experience_years = $request->experience_years;
            $matchMakerProfile->matchmaking_type = json_encode($matchmakingType); // array (json)
            $matchMakerProfile->preferred_age_min = $request->preferred_age_min;
            $matchMakerProfile->preferred_age_max = $request->preferred_age_max;
            $matchMakerProfile->preferred_gender = $request->preferred_gender;
            $matchMakerProfile->coverage_area = json_encode($coverageArea); // array (json)
            $matchMakerProfile->success_story = $request->success_story;
            $matchMakerProfile->total_matches = $request->total_matches;
            $matchMakerProfile->id_document = $documentPath ?? $matchMakerProfile->id_document;
            $matchMakerProfile->is_verified = false;
            $matchMakerProfile->is_approved = true;
            $matchMakerProfile->save();

            return $this->success(message: 'success');
        }
    catch (\Throwable $th) {}
            return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
    }
    //getProfile
    public function getProfile(Request $request)
    {
        try {
            $authUser = auth()->user();
            $user = User::query()->where('id', $authUser->id)->first();
            if (!$user) {
                return $this->error(message: 'User not found', code: 404);
            }

            return $this->success(
                message: 'User profile fetched successfully',
                data: [
                    'user' => new profileResource($user),
                ]
            );
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
        }
    }

    //storeMentorProfile
        public function storeMentorProfile(mentorProfileRequest $request)
        {
            try {
                $authUser = auth()->user();

                $mentorProfile = MentorProfile::query()->where('user_id', $authUser->id)->first();
                if (!$mentorProfile) {
                    return $this->notFound(message: 'not found', code: 404);
                }
                $mentorProfile->phone = $request->phone;
                $mentorProfile->price = $request->price ?? $mentorProfile->price;
                $mentorProfile->is_approved = true; // Set approval status as needed
                $mentorProfile->save();

                return $this->success(message: 'Mentor profile updated successfully');
            } catch (\Throwable $th) {
                return $this->error(message: $th->getMessage(), code: (int)$th->getCode());
            }
        }
        //updateProfile

    public function updateProfile(updateProfileRequest $request)
    {
        try {
            $authUser = auth()->user();

            if($request->lifestyle_preference){
                $lifestylePreference = array_map(
                    'trim',
                    explode(',', $request->lifestyle_preference)
                );
            }
            if($request->your_interest) {
                $yourInterest = array_map(
                    'trim',
                    explode(',', $request->your_interest)
                );
            }

            if ($request->hasFile('emirate_id')) {
                $emirateId = ImageService::updateImage('images/user/emirate_id', $request->file('emirate_id'),$authUser->emirate_id, 'emirate_');
                $authUser->emirate_id = $emirateId;
            }
            if ($request->hasFile('profile_image')) {
                $profileImage = ImageService::updateImage('images/user/profile_image', $request->file('profile_image'), $authUser->profile_image,'profile_image_');
                $authUser->profile_image = $profileImage;
            }

            $authUser->f_name = $request->f_name ?? $authUser->f_name;
            $authUser->age = $request->age ?? $authUser->age;
            $authUser->gender = $request->gender ?? $authUser->gender;
            $authUser->nationality = $request->nationality ?? $authUser->nationality;
            $authUser->profession = $request->profession ?? $authUser->profession;
            $authUser->company = $request->company ?? $authUser->company;
            $authUser->dubai_location = $request->dubai_location ?? $authUser->dubai_location;
            $authUser->height = $request->height ?? $authUser->height;
            $authUser->education_level = $request->education_level ?? $authUser->education_level;
            $authUser->family = $request->family ?? $authUser->family;
            $authUser->lifestyle_preference = $lifestylePreference ?? $authUser->lifestyle_preference;
            $authUser->your_interest = $yourInterest ?? $authUser->your_interest;

            $authUser->bio = $request->bio ?? $authUser->bio;
            $authUser->linkedin_profile = $request->linkedin_profile ?? $authUser->linkedin_profile;
            $authUser->save();

            return $this->success(
                message: 'User update successfully',
            );

        } catch (\Throwable $th) {
            return $this->error(
                message: $th->getMessage(),
            );
        }
    }

}
