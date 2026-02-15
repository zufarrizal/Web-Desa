<?php

namespace App\Models;

use CodeIgniter\Model;

class LetterSettingModel extends Model
{
    protected $table         = 'letter_settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'regency_name',
        'subdistrict_name',
        'village_name',
        'office_address',
        'app_icon',
        'signer_title',
        'signer_name',
        'signer_signature',
        'letterhead_address',
    ];
    protected $useTimestamps = true;
}
