<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table         = 'complaints';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'title',
        'content',
        'location',
        'image_path',
        'status',
        'response',
    ];
    protected $useTimestamps = true;
}
