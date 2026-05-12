<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ContactItemModel extends Model
{
    protected $table            = 'contact_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /** @var list<string> */
    protected $allowedFields = [
        'sort_order',
        'kind',
        'column_group',
        'title',
        'description',
        'meta_label',
        'href',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps      = true;
    protected $dateFormat         = 'datetime';
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
}
