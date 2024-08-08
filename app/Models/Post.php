<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Post extends Model {
    use HasSlug, HasFactory;

    /**
     * @var string
     */
    protected $table = 'posts';
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'post_category_id',
        'title',
        'forewords',
        'thumbnail',
        'content',
        'published'
    ];

    protected $hidden = [
        'user_id',
        'post_category_id'
    ];

    protected $casts = [
        'published' => 'boolean',
        'forewords'=> PurifyHtmlOnGet::class,
        'content' => PurifyHtmlOnGet::class,
        'created_at' => 'datetime:Y.m.d, H:i',
    ];

    public function getSlugOptions() : SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // Relationships
    public function category(): HasOne {
        return $this->hasOne(PostCategory::class,'id','post_category_id')->select('id','name');
    }

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','name');
    }

    public function media(): HasMany {
        return $this->hasMany(PostImage::class, 'post_id', 'id');
    }
}
