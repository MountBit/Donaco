<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donation;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics from models
        $currentMonthDonations = Donation::getCurrentMonthDonations();
        $yearTotalAmount = Donation::getYearTotalAmount();
        $lastMonthDonations = Donation::getLastMonthDonations();
        
        // Calculate growth percentage
        $donationGrowth = $lastMonthDonations > 0 
            ? round((($currentMonthDonations - $lastMonthDonations) / $lastMonthDonations) * 100, 1)
            : 0;

        // Get all other statistics from models
        $totalApprovedDonations = Donation::getTotalApprovedDonations();
        $currentMonthTotalValue = Donation::getCurrentMonthTotalValue();
        $totalPendingDonations = Donation::getTotalPendingDonations();
        $currentMonthPendingValue = Donation::getCurrentMonthPendingValue();
        $activeProjects = Project::getActiveProjectsCount();
        $totalDonors = Donation::getTotalUniqueDonors();
        $recentDonations = Donation::getRecentApprovedDonations();
        $chartData = Donation::getProjectsDonationsChart();

        return view('dashboard', compact(
            'chartData',
            'currentMonthDonations',
            'yearTotalAmount',
            'donationGrowth',
            'activeProjects',
            'totalDonors',
            'recentDonations',
            'totalApprovedDonations',
            'currentMonthTotalValue',
            'totalPendingDonations',
            'currentMonthPendingValue'
        ));
    }
}
