<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'community_id', 'content', 'image'];

    protected $with = ['user', 'likes', 'comments'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user')->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
