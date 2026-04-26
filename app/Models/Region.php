<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Region extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'state',
        'soil_type',
        'climate',
        'key',
        'rainfall_range',
        'season',
        'health_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the land holding for the region.
     */
    public function landHolding(): HasOne
    {
        return $this->hasOne(LandHolding::class);
    }

    /**
     * Get the irrigations for the region.
     */
    public function irrigations(): HasMany
    {
        return $this->hasMany(Irrigation::class);
    }

    /**
     * Get the cropping patterns for the region.
     */
    public function croppingPatterns(): HasMany
    {
        return $this->hasMany(CroppingPattern::class);
    }
}
