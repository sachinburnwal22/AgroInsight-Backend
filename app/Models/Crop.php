<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'water_requirement',
        'season',
        'why_grown',
        'ideal_soil',
        'market_demand',
        'government_support',
        'emoji',
        'expected_yield',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'water_requirement' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the cropping patterns for the crop.
     */
    public function croppingPatterns(): HasMany
    {
        return $this->hasMany(CroppingPattern::class);
    }
}
