<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model {
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'img_name',
        'img_path',
        'description'
    ];
}
