<?php

namespace App\Http\Resources\api\v1\referrals;

use App\Http\Resources\api\v1\auth\UserListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $referralCode = $this->referral_code ?? 'BLING-DEFAULT';
        $shareLink = config('app.url') . '/invite?code=' . $referralCode;

        return [
            'referral_code' => $referralCode,
            'share_link' => $shareLink,
            'total_referrals_count' => $this->referrals()->count(),
            'referred_friends' => UserListResource::collection($this->referrals()->latest()->get()),
        ];
    }
}
