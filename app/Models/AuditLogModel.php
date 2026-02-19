<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table         = 'audit_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id',
        'user_name',
        'user_role',
        'method',
        'uri',
        'action',
        'status_code',
        'ip_address',
        'user_agent',
        'payload',
        'created_at',
    ];
    protected $useTimestamps = false;
}

