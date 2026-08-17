<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\promocode\storePromoCodeRequest;
use App\Http\Requests\api\v1\admin\promocode\updatePromoCodeRequest;
use App\Http\Resources\api\v1\admin\promocode\PromoCodeResource;
use App\Models\PromoCode;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Admin · Promo Code', weight: 4)]
class PromoCodeController extends Controller
{
    /**
     * Get list of promo codes
     */
    public function index(Request $request)
    {
        $promoCodes = PromoCode::query()
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->get();

        return $this->success(
            message: 'Promo codes fetched successfully',
            data: PromoCodeResource::collection($promoCodes)
        );
    }

    /**
     * Get single promo code detail
     */
    public function show($id)
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound(message: 'Promo code not found');
        }

        return $this->success(
            message: 'Promo code fetched successfully',
            data: PromoCodeResource::make($promoCode)
        );
    }

    /**
     * Create a new promo code
     */
    public function store(storePromoCodeRequest $request)
    {
        $promoCode = PromoCode::create([
            'code' => strtoupper($request->code),
            'max_uses' => $request->max_uses,
            'start_date' => $request->start_date,
            'expires_at' => $request->expires_at,
            'status' => $request->status ?? 'active',
            'created_by' => auth()->id(),
        ]);

        return $this->success(
            message: 'Promo code created successfully',
            data: PromoCodeResource::make($promoCode)
        );
    }

    /**
     * Update an existing promo code
     */
    public function update(updatePromoCodeRequest $request, $id)
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound(message: 'Promo code not found');
        }

        if ($request->filled('code')) {
            $promoCode->code = strtoupper($request->code);
        }
        if ($request->has('max_uses')) {
            $promoCode->max_uses = $request->max_uses;
        }
        if ($request->has('start_date')) {
            $promoCode->start_date = $request->start_date;
        }
        if ($request->has('expires_at')) {
            $promoCode->expires_at = $request->expires_at;
        }
        if ($request->filled('status')) {
            $promoCode->status = $request->status;
        }

        $promoCode->save();

        return $this->success(
            message: 'Promo code updated successfully',
            data: PromoCodeResource::make($promoCode)
        );
    }

    /**
     * Delete a promo code
     */
    public function destroy($id)
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound(message: 'Promo code not found');
        }

        $promoCode->delete();

        return $this->success(
            message: 'Promo code deleted successfully'
        );
    }
}
