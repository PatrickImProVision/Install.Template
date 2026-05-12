<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class AboutUsItemModel extends Model
{
    protected $table            = 'about_us_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /** @var list<string> */
    protected $allowedFields = [
        'sort_order',
        'placement',
        'kind',
        'title',
        'description',
        'href',
        'icon_key',
        'bullets',
        'footnote',
        'card_style',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps      = true;
    protected $dateFormat         = 'datetime';
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
}
