<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class SeoPageModel extends Model
{
    protected $table            = 'seo_pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /** @var list<string> */
    protected $allowedFields = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps      = true;
    protected $dateFormat         = 'datetime';
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
}
