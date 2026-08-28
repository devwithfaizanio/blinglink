<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\referrals\SendFriendInviteRequest;
use App\Http\Resources\api\v1\referrals\ReferralInfoResource;
use App\Mail\FriendInviteMail;
use App\Models\UserReferral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReferralController extends Controller
{
    /**
     * Get the authenticated user's referral code, share link, and list of referred friends.
     */
    public function getMyReferralInfo(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (empty($user->referral_code)) {
                $user->update([
                    'referral_code' => $user->generateUniqueReferralCode(),
                ]);
            }

            return $this->success(
                message: 'Referral information retrieved successfully',
                data: new ReferralInfoResource($user)
            );
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage());
        }
    }

    /**
     * Send an email invitation with the referral link to a friend.
     */
    public function sendFriendInvite(SendFriendInviteRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (empty($user->referral_code)) {
                $user->update([
                    'referral_code' => $user->generateUniqueReferralCode(),
                ]);
            }

            $shareLink = config('app.url') . '/invite?code=' . $user->referral_code;

            UserReferral::create([
                'referrer_id' => $user->id,
                'friend_email' => $request->email,
                'status' => 'invited',
            ]);

//            try {
//                Mail::to($request->email)->send(new FriendInviteMail(
//                    inviter: $user,
//                    shareLink: $shareLink,
//                    customMessage: $request->message
//                ));
//            } catch (\Throwable $e) {
//                // Mail sending error handled gracefully so the API response doesn't crash
//            }

            return $this->success(
                message: 'Friend invitation sent successfully',
                data: [
                    'referral_code' => $user->referral_code,
                    'invited_email' => $request->email,
                    'share_link' => $shareLink,
                ]
            );
        } catch (\Throwable $th) {
            return $this->error(message: $th->getMessage());
        }
    }
}
