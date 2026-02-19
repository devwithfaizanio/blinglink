<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{

    public function Index()
    {
        return view('auth.reset-password');
    }

    public function ResetPassword(Request $request)
    {

        try {
            $data = $request->all();
            $current_password = $data['current_password'];
            $check_password = User::query()->find(auth()->user()->id);
            if (Hash::check($current_password, $check_password->password)) {
                $password = bcrypt($data['new_password']);
                $check_password->update(['password' => $password]);
                return redirect()->back()->with('success_message', 'Password Updated Successfully!');
            } else {
                return redirect()->back()->with('error_message', 'Incorrect Current Password!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', $e->getMessage());
        }
    }
}
