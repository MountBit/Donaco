<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'donations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_reference',
        'nickname',
        'email',
        'message',
        'status',
        'value',
        'phone',
        'project_id',
        'message_hidden',
        'message_hidden_reason'
    ];

    /**
     * Get the project associated with the donation.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function calculateProjectProgress($projectId)
    {
        $totalDonations = self::where('project_id', $projectId)
            ->where('status', 'approved')
            ->sum('value');

        $project = Project::find($projectId);
        if (!$project || $project->goal <= 0) {
            return 0;
        }

        return min(100, round(($totalDonations / $project->goal) * 100, 1));
    }

    /**
     * Acessor para o valor formatado.
     *
     * @return string
     */
    public function getFormattedValueAttribute()
    {
        return number_format($this->value, 2, ',', '.');
    }

    // Dashboard Statistics Methods
    public static function getCurrentMonthDonations()
    {
        return self::where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public static function getYearTotalAmount()
    {
        return self::where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->sum('value');
    }

    public static function getLastMonthDonations()
    {
        return self::where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month - 1)
            ->count();
    }

    public static function getTotalApprovedDonations()
    {
        return self::where('status', 'approved')->count();
    }

    public static function getCurrentMonthTotalValue()
    {
        return self::where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('value');
    }

    public static function getTotalPendingDonations()
    {
        return self::where('status', 'pending')->count();
    }

    public static function getCurrentMonthPendingValue()
    {
        return self::where('status', 'pending')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('value');
    }

    public static function getTotalUniqueDonors()
    {
        return self::where('status', 'approved')
            ->distinct('nickname')
            ->count('nickname');
    }

    public static function getRecentApprovedDonations($limit = 5)
    {
        return self::with('project')
            ->where('status', 'approved')
            ->latest()
            ->take($limit)
            ->get();
    }

    public static function getProjectsDonationsChart()
    {
        $projectsDonations = self::where('status', 'approved')
            ->selectRaw('project_id, SUM(value) as total')
            ->groupBy('project_id')
            ->with('project')
            ->get();

        return [
            'labels' => $projectsDonations->pluck('project.name'),
            'data' => $projectsDonations->pluck('total')
        ];
    }
}
