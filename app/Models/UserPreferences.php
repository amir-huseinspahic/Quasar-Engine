<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';

    protected $fillable = [
        'user_id',
        'language',
        'locale',
        'timezone',
        'date_format',
        'time_format',
        'items_per_page',
        'items_preview_layout'
    ];
}
