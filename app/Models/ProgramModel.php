<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table         = 'programs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'title',
        'post_type',
        'slug',
        'excerpt',
        'image_path',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at',
    ];
    protected $useTimestamps = true;
}
