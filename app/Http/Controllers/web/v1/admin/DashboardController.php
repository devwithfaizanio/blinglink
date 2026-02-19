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

        $totalUsers = User::query()
            ->where('role', 'user')
            ->count();
        $totalRecipes = RecipesDiet::query()
            ->count();
        $totalWorkouts = Workout::query()
            ->count();
        $totalFastType = Fast::query()
            ->count();
        $totalAnnouncements = Announcement::query()
            ->count();
        $openTickets = SupportTicket::query()
            ->where('status', 'open')
            ->count();

        // Step 1: Get last 12 months (month name + year, e.g., "May 2025")
        $last12Months = collect(range(0, 11))
            ->map(fn($i) => Carbon::now()->subMonths(11 - $i))
            ->values();

// Step 2: Get user count grouped by month (still using Y-m for matching)
        $userCounts = User::query()
            ->where('role', 'user')
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
            ->where('created_at', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

// Step 3: Build graph data
        $graphData = [
            'months' => $last12Months->map(fn($date) => $date->format('F Y'))->toArray(), // e.g., "May 2025"
            'users' => $last12Months->map(fn($date) => $userCounts[$date->format('Y-m')] ?? 0)->toArray()
        ];

        //sum of user
        $last12MonthsUsersRegistered = array_sum($graphData['users']);



        return view('backend.admin.dashboard',
            [
                'totalUsers' => $totalUsers,
                'totalRecipes' => $totalRecipes,
                'totalWorkouts' => $totalWorkouts,
                'totalFastType' => $totalFastType,
                'totalAnnouncements' => $totalAnnouncements,
                'openTickets' => $openTickets,
                'graphData' => $graphData,
                'last12MonthsUsersRegistered' => $last12MonthsUsersRegistered,
            ]
        );
    }
}
