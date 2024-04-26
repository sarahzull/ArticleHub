<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'is_premium',
        'is_featured',
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

    protected $casts = [
        'published_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'published_at_date',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPublishedAtDateAttribute()
    {
        $publishedAt = Carbon::parse($this->published_at);
        $now = Carbon::now();
        $diffInHours = $publishedAt->diffInHours($now);

        if ($diffInHours < 24) {
            return $publishedAt->diffInHours($now) . ' hours ago';
        } else {
            return $publishedAt->format('d F Y');
        }
    }
}
