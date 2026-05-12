<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingsModel extends Model
{
    protected $table            = 'site_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'site_name', 'site_description', 'updated_at'];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps = false;
}
