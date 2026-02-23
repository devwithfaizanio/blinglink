<?php

namespace App\Http\Controllers\web\v1\admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Fast;
use App\Models\RecipesDiet;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Workout;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function Dashboard()
    {
        return view('backend.admin.dashboard');
    }
}
