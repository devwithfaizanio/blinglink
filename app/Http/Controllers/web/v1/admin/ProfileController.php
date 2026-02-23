<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function Profile()
    {
        return view('backend.admin.profile');
    }

    public function ProfilePost(Request $request)
    {
//        dd($request->all());
        try {
            $user = User::query()->findOrFail(auth()->user()->id);
            $user->f_name = $request->input('f_name') ?? $user->f_name;
            if ($request->hasFile('image')) {
                $user->profile_image = ImageService::updateImage('images/user/profile_image',$request->image, $user->profile_image, 'Profile_');
            }
            $user->save();


            $notification = array(
                'message' => 'Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
