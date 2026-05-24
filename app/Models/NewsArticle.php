<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'ai_summary',
        'content',
        'url',
        'image',
        'source',
        'category',
        'published_at',
        'state',
        'crop',
        'is_trending',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_trending' => 'boolean',
    ];

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_articles', 'article_id', 'user_id');
    }
}
