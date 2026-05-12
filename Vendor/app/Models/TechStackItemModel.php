<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class TechStackItemModel extends Model
{
    protected $table            = 'tech_stack_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /** @var list<string> */
    protected $allowedFields = [
        'sort_order',
        'kind',
        'title',
        'description',
        'category',
        'product_name',
        'blurb',
        'href',
        'icon_color',
        'name_color',
        'icon_svg',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps      = true;
    protected $dateFormat         = 'datetime';
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
}
