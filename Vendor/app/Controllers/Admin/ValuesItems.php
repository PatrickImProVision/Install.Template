<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ValuesItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class ValuesItems extends BaseController
{
    private function valuesTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('values_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const KINDS = ['page_heading', 'value_item'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->valuesTableExists()) {
            return view('admin/values_items/schema_missing', ['title' => 'Our values — setup needed']);
        }

        $items = model(ValuesItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/values_items/index', [
            'title' => 'Our values — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->valuesTableExists()) {
            return view('admin/values_items/schema_missing', ['title' => 'Our values — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/values_items/form', [
                    'title'   => 'New Values block',
                    'action'  => site_url('DashBoard/Values/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncValuesItemsIdSequenceForPostgres();

            $model = model(ValuesItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/values_items/form', [
                    'title'   => 'New Values block',
                    'action'  => site_url('DashBoard/Values/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Values'))->with('success', 'Block created.');
        }

        return view('admin/values_items/form', [
            'title'  => 'New Values block',
            'action' => site_url('DashBoard/Values/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->valuesTableExists()) {
            return view('admin/values_items/schema_missing', ['title' => 'Our values — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(ValuesItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Values'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/values_items/form', [
                    'title'   => 'Edit Values block',
                    'action'  => site_url('DashBoard/Values/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(ValuesItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/values_items/form', [
                    'title'   => 'Edit Values block',
                    'action'  => site_url('DashBoard/Values/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Values'))->with('success', 'Block updated.');
        }

        return view('admin/values_items/form', [
            'title'  => 'Edit Values block',
            'action' => site_url('DashBoard/Values/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->valuesTableExists()) {
            return redirect()->to(site_url('DashBoard/Values'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/Values'));
        }

        model(ValuesItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/Values'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'          => null,
            'sort_order'  => 0,
            'kind'        => 'value_item',
            'title'       => '',
            'description' => '',
            'emoji'       => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizedPostRow(): array
    {
        $p    = $this->request->getPost();
        $kind = (string) ($p['kind'] ?? 'value_item');
        $emo  = trim((string) ($p['emoji'] ?? ''));

        return [
            'sort_order'  => (int) ($p['sort_order'] ?? 0),
            'kind'        => $kind,
            'title'       => (string) ($p['title'] ?? ''),
            'description' => (string) ($p['description'] ?? ''),
            'emoji'       => $kind === 'value_item' && $emo !== '' ? $emo : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validationPost(): array
    {
        $post = $this->request->getPost();
        if (! is_array($post)) {
            return [];
        }

        $post['emoji'] = trim((string) ($post['emoji'] ?? ''));

        return $post;
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        $kindList = implode(',', self::KINDS);

        return [
            'sort_order'  => 'permit_empty|integer',
            'kind'        => 'required|in_list[' . $kindList . ']',
            'title'       => 'permit_empty|max_length[255]',
            'description' => 'permit_empty|max_length[65535]',
            'emoji'       => 'permit_empty|max_length[64]',
        ];
    }

    private function syncValuesItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'values_items';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
