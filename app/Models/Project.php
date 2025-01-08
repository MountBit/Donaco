<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'goal',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'goal' => 'decimal:2'
    ];

    protected $appends = ['formatted_goal'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // Métodos para estatísticas
    public static function getActiveProjectsCount()
    {
        return static::where('is_active', true)->count();
    }

    public static function getTotalProjects()
    {
        return static::count();
    }

    public static function getProjectsWithMostDonations($limit = 5)
    {
        return static::withCount(['donations' => function($query) {
            $query->where('status', 'approved');
        }])
        ->orderByDesc('donations_count')
        ->limit($limit)
        ->get();
    }

    public static function getProjectProgress($projectId)
    {
        $project = static::with(['donations' => function($query) {
            $query->where('status', 'approved');
        }])->findOrFail($projectId);

        $totalDonations = $project->donations->sum('value');
        return $project->goal > 0 ? ($totalDonations / $project->goal) * 100 : 0;
    }

    /**
     * Formata o valor para exibição em BRL
     */
    public function getFormattedGoalAttribute(): string
    {
        return 'R$ ' . number_format($this->goal, 2, ',', '.');
    }
}
