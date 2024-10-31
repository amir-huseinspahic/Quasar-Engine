<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AppSettings extends Model {
    use HasFactory;

    protected $table = 'app_settings';

    protected $fillable = [
        'ai_moderation',
        'posts',
        'gallery'
    ];

    protected $casts = [
        'ai_moderation' => 'boolean',
        'posts' => 'boolean',
        'gallery' => 'boolean'
    ];
}
