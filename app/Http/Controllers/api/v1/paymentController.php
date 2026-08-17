<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\payment\connectToMatchmakeRequest;
use App\Http\Requests\api\v1\payment\connectToMentorRequest;
use App\Http\Resources\api\v1\payment\myMatchMakerResource;
use App\Http\Resources\api\v1\payment\myMentorResource;
use App\Models\ConnectionToMatchmaker;
use App\Models\ConnectionToMentor;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class paymentController extends Controller
{
    public function connectToMatchmaker(connectToMatchmakeRequest $request)
    {
        $authUser = auth()->user();

        if ($authUser->role !== 'user') {
            return $this->forbidden(
                message: 'Only users can connect to matchmakers.'
            );
        }

        // 🔥 Check if already connected
        $alreadyConnected = ConnectionToMatchmaker::where('user_id', $authUser->id)
            ->where('matchmaker_id', $request->matchmaker_id)
            ->exists();

        if ($alreadyConnected) {
            return $this->error(
                message: 'You are already connected to this matchmaker.'
            );
        }

        DB::beginTransaction();

        try {

            $paymentHistory = PaymentHistory::create([
                'from_id' => $authUser->id,
                'to_id' => $request->matchmaker_id,
                'amount' => $request->amount,
                'type' => 'matchmaker',
            ]);

            ConnectionToMatchmaker::create([
                'payment_history_id' => $paymentHistory->id,
                'user_id' => $authUser->id,
                'matchmaker_id' => $request->matchmaker_id,
            ]);

            DB::commit();

            return $this->success(
                message: 'Connected to matchmaker successfully.',
            );

        } catch (\Throwable $th) {

            DB::rollBack();

            return $this->error(
                message: $th->getMessage(),
            );
        }
    }
    public function connectToMentor(connectToMentorRequest $request)
    {
        $authUser = auth()->user();

        if ($authUser->role !== 'user') {
            return $this->forbidden(
                message: 'Only users can connect to matchmakers.'
            );
        }

        // 🔥 Check if already connected
        $alreadyConnected = ConnectionToMentor::where('user_id', $authUser->id)
            ->where('mentor_id', $request->mentor_id)
            ->exists();

        if ($alreadyConnected) {
            return $this->error(
                message: 'You are already connected to this mentor.'
            );
        }

        DB::beginTransaction();

        try {

            $paymentHistory = new PaymentHistory();
            $paymentHistory->from_id = $authUser->id;
            $paymentHistory->to_id = $request->mentor_id;
            $paymentHistory->amount = $request->amount;
            $paymentHistory->type = 'mentor';
            $paymentHistory->save();

            $connectToMatchmaker = new ConnectionToMentor();
            $connectToMatchmaker->payment_history_id = $paymentHistory->id;
            $connectToMatchmaker->user_id = $authUser->id;
            $connectToMatchmaker->mentor_id = $request->mentor_id;
            $connectToMatchmaker->save();

            DB::commit();

            return $this->success(
                message: 'Connected to mentor successfully.',
            );

        } catch (\Throwable $th) {

            DB::rollBack();

            return $this->error(
                message: $th->getMessage(),
            );
        }
    }

    //myMentors
    public function myMentors()
    {
        $authUser = auth()->user();

//        if ($authUser->role !== 'user') {
//            return $this->forbidden(
//                message: 'Only users can view their mentors.'
//            );
//        }

        $mentors = ConnectionToMentor::query()->where('user_id', $authUser->id)->get();

        return $this->success(
            message: 'My mentors retrieved successfully.',
            data: myMentorResource::collection($mentors),

        );
    }

    //myMatchmakers
    public function myMatchmakers()
    {
        $authUser = auth()->user();

//        if ($authUser->role !== 'user') {
//            return $this->forbidden(
//                message: 'Only users can view their matchmakers.'
//            );
//        }

        $matchmakers = ConnectionToMatchmaker::query()->where('user_id', $authUser->id)->get();

        return $this->success(
            message: 'My matchmakers retrieved successfully.',
            data: myMatchMakerResource::collection($matchmakers),
        );
    }
}
