<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Index
    public function index()
    {
        $users = User::query()->where('role', '!=','admin')->latest()->get();
        return view('backend.admin.users.index',compact('users'));
    }
    //Destroy
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        //image
        if ($user->profile_image) {
           ImageService::deleteImage( 'images/user/profile_image/',$user->profile_image);
        }
        if ($user->emirate_id) {
            ImageService::deleteImage( 'images/user/emirate_id/',$user->emirate_id);
        }
        $user->delete();
        $notification = array(
            'message' => 'Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //toggleStatus
    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
//        dd($user);

        $user->is_approved = !$user->is_approved; // toggle true/false
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->is_approved
        ]);
    }


}
