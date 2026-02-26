<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function Dashboard()
    {
        $totalUser  = User::query()->where('role', 'user')->count();
        $totalMatcherMaker  = User::query()->where('role', 'matchmaker')->count();
        $totalMentor  = User::query()->where('role', 'mentor')->count();
        return view('backend.admin.dashboard',[
            'totalUser' => $totalUser,
            'totalMatcherMaker' => $totalMatcherMaker,
            'totalMentor' => $totalMentor,
        ]);
    }
}
