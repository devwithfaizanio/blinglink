<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\web\v1\ForgotPassword;
use App\Mail\v1\ForgotPasswordLinkMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function Index()
    {
        return view('auth.forgot-password');
    }

    public function submitForgotPasswordForm(ForgotPassword $request)
    {

        try {

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email], // Search condition
                ['token' => $token, 'created_at' => Carbon::now()] // Data to update/insert
            );


//            Mail::send('mails.send-forgot-password-mail', ['token' => $token], function ($message) use ($request) {
//                $message->to($request->email);
//                $message->subject('Reset Password');
//            });
            Mail::to($request->email)->queue(new ForgotPasswordLinkMail($token));
            Session::flash('link_success', ' Reset Link Send Successfully!');

            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('link_error', $e->getMessage());

            return redirect()->back();
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('mails.forgot-password-form', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token,
                ])
                ->first();

            if (! $updatePassword) {
                return back()->withInput()->with('error_message', 'Invalid token!');
            }

            $user = User::query()->where('email', $request->email)
                ->update(['password' => Hash::make($request->new_password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            $userRole = User::query()->where('email',$request->email)->first();
            if($userRole->role == 'coach'){
                return redirect('/')->with('register_success', 'Your password has been changed!');
            }else{
                return redirect('/admin-login')->with('register_success', 'Your password has been changed!');
            }


        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', $e->getMessage());

        }
    }
}
