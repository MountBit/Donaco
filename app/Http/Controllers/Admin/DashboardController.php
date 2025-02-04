<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donation;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas básicas
        $currentMonthDonations = Donation::getCurrentMonthDonations();
        $yearTotalAmount = Donation::getYearTotalAmount();
        $lastMonthDonations = Donation::getLastMonthDonations();

        // Crescimento percentual
        $donationGrowth = $lastMonthDonations > 0
            ? round((($currentMonthDonations - $lastMonthDonations) / $lastMonthDonations) * 100, 1)
            : 0;

        // Estatísticas adicionais
        $activeProjects = Project::getActiveProjectsCount();
        $totalDonors = Donation::distinct('email')->count();
        $totalApprovedDonations = Donation::where('status', 'approved')->count();
        $currentMonthTotalValue = Donation::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('value');
        $totalPendingDonations = Donation::where('status', 'pending')->count();
        $currentMonthPendingValue = Donation::where('status', 'pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('value');

        // Doações recentes
        $recentDonations = Donation::with('project')
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        // Dados do gráfico de distribuição por projeto
        $projectDonations = Donation::select('projects.name', DB::raw('SUM(donations.value) as total'))
            ->join('projects', 'donations.project_id', '=', 'projects.id')
            ->where('donations.status', 'approved')
            ->groupBy('projects.id', 'projects.name')
            ->get();

        $chartData = [
            'labels' => $projectDonations->pluck('name')->toArray(),
            'data' => $projectDonations->pluck('total')->map(function ($value) {
                return number_format($value, 2, '.', '');
            })->toArray()
        ];

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
