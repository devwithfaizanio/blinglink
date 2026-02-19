<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function Index()
    {

        return view('auth.admin_login');
    }
    public function AdminLogin(Request $request)
    {
        try {
            $user = User::query()->where('email', $request->email)->where('role', 'admin')->first();
            if (! $user) {
                $alert['type'] = 'danger';
                $alert['heading'] = 'login failed';
                $alert['message'] = 'Invalid Email or Password';

                return redirect()->back()->with('login_error', $alert);
            }
            if (! auth()->loginUsingId((password_verify($request->password, $user->password)) ? $user->id : 0)) {
                $alert['type'] = 'danger';
                $alert['heading'] = 'login failed';
                $alert['message'] = 'Invalid  password';

                return redirect()->back()->with('login_error', $alert);
            }
            if (auth()->check() and auth()->user()->role === 'admin') {
                return redirect('/admin');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('login_error_exception', $e->getMessage());
        }
    }

    public function logout()
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                auth()->logout();

                return redirect('/');
            }
            elseif(auth()->user()->role === 'coach'){
                auth()->logout();
                return redirect('/');
            }
//            elseif(auth()->user()->type === 'merchant'){
//                auth()->logout();
//                return redirect('/');
//            }
        } else {
            return redirect('/');
        }
    }
}
