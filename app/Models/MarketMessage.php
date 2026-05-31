<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketMessage extends Model
{
    use HasFactory;

    protected $table = 'market_messages';

    protected $fillable = [
        'session_id',
        'sender_id',
        'message',
    ];

    public function session()
    {
        return $this->belongsTo(MarketSession::class, 'session_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
