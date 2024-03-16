<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'author_id',
        'is_premium',
        'category_id',
        'title',
        'content',
        'published_at',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];
}
