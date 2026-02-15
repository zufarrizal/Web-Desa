<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'name',
        'email',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'occupation',
        'marital_status',
        'address',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',
        'citizenship',
        'password',
        'role',
        'last_login_at',
    ];
    protected $useTimestamps = true;
}
