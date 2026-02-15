<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentRequestModel extends Model
{
    protected $table         = 'document_requests';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'citizen_name',
        'nik',
        'document_type',
        'description',
        'status',
        'admin_notes',
    ];
    protected $useTimestamps = true;
}
