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
        'village_profile_title',
        'village_profile_content',
        'announcement_title',
        'announcement_content',
        'contact_person',
        'contact_phone',
        'contact_email',
        'contact_whatsapp',
        'complaint_info',
        'office_latitude',
        'office_longitude',
        'office_map_plus_code',
        'recaptcha_enabled',
        'recaptcha_site_key',
        'recaptcha_secret_key',
    ];
    protected $useTimestamps = true;
}
