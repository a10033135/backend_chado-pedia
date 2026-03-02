<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChadoContent extends Model
{
    protected $table = 'ChadoContent';
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

    public function mainCategories()
    {
        return $this->belongsToMany(MainCate::class, 'ChadoContent_MainCate', 'chado_content_id', 'main_cate_id');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCate::class, 'ChadoContent_SubCate', 'chado_content_id', 'sub_cate_id');
    }
}
