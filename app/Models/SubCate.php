<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCate extends Model
{
    protected $table = 'SubCate';
    public $timestamps = false;

    protected $fillable = [
        'main_cate_id',
        'title',
        'description',
        'sort',
        'has_image',
        'enable',
        'create_time',
        'update_time',
    ];

    protected $casts = [
        'has_image' => 'boolean',
        'enable' => 'boolean',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCate::class, 'main_cate_id');
    }

    public function chadoContents()
    {
        return $this->belongsToMany(ChadoContent::class, 'ChadoContent_SubCate', 'sub_cate_id', 'chado_content_id');
    }
}
