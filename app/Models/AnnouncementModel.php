<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table         = 'announcements';
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
