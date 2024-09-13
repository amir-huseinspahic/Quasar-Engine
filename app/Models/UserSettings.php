<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model {
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'locale',
        'timezone',
        'items_per_page',
        'page_layout',
        'time_format',
        'date_format',
        'show_private_posts'
    ];

    protected function casts() : array {
        return [
            'show_private_posts' => 'boolean'
        ];
    }
}
