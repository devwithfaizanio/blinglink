<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\v1\ImageService;
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
            $user->fname = $request->input('fname') ?? $user->fname;
            $user->lname = $request->input('lname') ?? $user->lname;
            if ($request->hasFile('image')) {
                $user->image = ImageService::updateImage('images/profile',$request->image, $user->image, 'Profile_');
            }
            $user->save();

            return redirect()->back()->with('success', 'update profile ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
