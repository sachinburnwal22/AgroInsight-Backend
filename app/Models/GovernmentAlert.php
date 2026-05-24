<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'state',
        'severity',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];
}
