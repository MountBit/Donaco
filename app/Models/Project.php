<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donation;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'goal',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the donations for the project.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public static function getActiveProjectsCount()
    {
        return self::where('is_active', true)->count();
    }
}
