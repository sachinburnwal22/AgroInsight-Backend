<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketSession extends Model
{
    use HasFactory;

    protected $table = 'market_sessions';

    protected $fillable = [
        'room_id',
        'host_id',
        'guest_id',
        'status',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function messages()
    {
        return $this->hasMany(MarketMessage::class, 'session_id');
    }
}
