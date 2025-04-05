<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'tags',
        'published',
        'views_count',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'views_count' => 'integer'
    ];

    public function getTagsAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($post) {
            if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                Storage::disk('public')->delete($post->featured_image);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('featured_image')) {
                $originalImage = $post->getOriginal('featured_image');
                if ($originalImage && Storage::disk('public')->exists($originalImage)) {
                    Storage::disk('public')->delete($originalImage);
                }
            }
        });
    }

    public function getReadingTimeAttribute()
    {
        return 1;
    }

    public function getCategoryColorAttribute()
    {
        return 'blue';
    }
}
