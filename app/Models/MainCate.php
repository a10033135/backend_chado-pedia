<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCate extends Model
{
    protected $table = 'MainCate';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'has_image',
        'enable',
        'create_time',
        'update_time',
    ];

    protected $casts = [
        'has_image' => 'boolean',
        'enable' => 'boolean',
    ];
}
