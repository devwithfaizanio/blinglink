<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\auth\ResetPasswordByOtp;
use App\Models\ForgotPasswordOtp;
use App\Models\User;
use App\Notifications\api\v1\ForgotPasswordOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
//        try {
            $messages = [
                'email.required' => 'The email field is required',
                'email.email' => 'The email must be a valid email address',
                'email.exists' => 'The selected email is invalid',
            ];

            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|exists:users',
                ],
                $messages
            );

            if ($validator->fails()) {
                return $this->error(message: $validator->messages()->first(),code: 422);
            }
            else{
                $user = User::query()->where('email', $request->email)->first();
                if ($user->role == 'admin' || $user->role == 'school') {
                    return $this->forbidden(message: 'Admin or school cannot forgot password here');
                }



                $token = Str::random(64);
                $otp = rand(100000, 999999);
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $request->email], // columns to check if the record exists
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => Carbon::now(),
                    ]
                );
                DB::table('forgot_password_otps')->insert([
                    'email' => $request->email,
                    'otp' => $otp,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
//                Mail::send('mails.send-forgot-password-mail-otp', [
//                    'token' => $token,
//                    'otp' => $otp,
//                ], function ($messages) use ($request) {
//                    $messages->to($request->email);
//                    $messages->subject('Reset Password');
//                });

                $user->notify(new ForgotPasswordOtpNotification($token, $otp));

                return $this->success(message: 'Mail Send Successfully',data: [
                    'token' => $token,
                    'otp' => $otp,
                    'email' => $request->email,
                ]);
            }
//        } catch (\Exception $th) {
//            return $this->error(message: $th->getMessage(),code: $th->getCode());
//        }
    }

    public function VerifyOtp(Request $request)
    {
        $messages = [
            'email.required' => 'The email field is required',
            'email.email' => 'The email must be a valid email address',
            'email.exists' => 'The selected email is invalid',
            'otp.required' => 'The OTP field is required',
            'otp.min' => 'The OTP must be at least 6 characters',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users',
                'otp' => 'required|min:6',
            ],
            $messages
        );
        if ($validator->fails()) {
            return $this->error(message: $validator->messages()->first(),code: 422);
        }
        $email = $request->input('email');
        $otp = $request->input('otp');

        // Retrieve the record from the database
        $forgotOtp = ForgotPasswordOtp::query()->where('email', $email)->orderBy('created_at', 'desc')->first();

        if (!$forgotOtp) {
            return response()->json(['error' => 'Invalid email']);
        }

        // Compare OTP and check if it's within 30 seconds and 300 is 5 minuts
        if ($forgotOtp->otp == $otp && now()->diffInSeconds($forgotOtp->created_at) <= 300) {
            return $this->success(message: 'OTP verified successfully');
        } else {
            return $this->error(message: 'Invalid OTP',code: 422);
        }
    }

    public function ResetPassword(ResetPasswordByOtp $request)
    {
        try {
            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token,
                ])
                ->first();

            if (! $updatePassword) {
                return $this->error(message: 'Invalid token!',code: 422);
            }
            $user = User::query()->where('email', $request->email)
                ->update(['password' => Hash::make($request->new_password)]);

            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
            //also delete the otp
            DB::table('forgot_password_otps')->where(['email' => $request->email])->delete();
            return $this->success(message: 'Your password has been changed!');
        } catch (\Exception $e) {
            return $this->error(message: $e->getMessage());
        }

    }
}
