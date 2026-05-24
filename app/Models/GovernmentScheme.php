<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'eligibility',
        'benefits',
        'apply_link',
        'state',
        'category',
    ];
}
