<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TechStackItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class TechStackItems extends BaseController
{
    private function techStackTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('tech_stack_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const KINDS = ['page_heading', 'tech_card'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->techStackTableExists()) {
            return view('admin/tech_stack_items/schema_missing', ['title' => 'Technology stack — setup needed']);
        }

        $items = model(TechStackItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/tech_stack_items/index', [
            'title' => 'Technology stack — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->techStackTableExists()) {
            return view('admin/tech_stack_items/schema_missing', ['title' => 'Technology stack — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/tech_stack_items/form', [
                    'title'   => 'New Technology block',
                    'action'  => site_url('DashBoard/Tech_Stack/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncTechStackItemsIdSequenceForPostgres();

            $model = model(TechStackItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/tech_stack_items/form', [
                    'title'   => 'New Technology block',
                    'action'  => site_url('DashBoard/Tech_Stack/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Tech_Stack'))->with('success', 'Block created.');
        }

        return view('admin/tech_stack_items/form', [
            'title'  => 'New Technology block',
            'action' => site_url('DashBoard/Tech_Stack/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->techStackTableExists()) {
            return view('admin/tech_stack_items/schema_missing', ['title' => 'Technology stack — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(TechStackItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Tech_Stack'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/tech_stack_items/form', [
                    'title'   => 'Edit Technology block',
                    'action'  => site_url('DashBoard/Tech_Stack/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(TechStackItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/tech_stack_items/form', [
                    'title'   => 'Edit Technology block',
                    'action'  => site_url('DashBoard/Tech_Stack/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Tech_Stack'))->with('success', 'Block updated.');
        }

        return view('admin/tech_stack_items/form', [
            'title'  => 'Edit Technology block',
            'action' => site_url('DashBoard/Tech_Stack/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->techStackTableExists()) {
            return redirect()->to(site_url('DashBoard/Tech_Stack'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/Tech_Stack'));
        }

        model(TechStackItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/Tech_Stack'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'           => null,
            'sort_order'   => 0,
            'kind'         => 'tech_card',
            'title'        => '',
            'description'  => '',
            'category'     => '',
            'product_name' => '',
            'blurb'        => '',
            'href'         => '',
            'icon_color'   => '',
            'name_color'   => '',
            'icon_svg'     => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizedPostRow(): array
    {
        $p = $this->request->getPost();

        return [
            'sort_order'   => (int) ($p['sort_order'] ?? 0),
            'kind'         => (string) ($p['kind'] ?? 'tech_card'),
            'title'        => (string) ($p['title'] ?? ''),
            'description'  => (string) ($p['description'] ?? ''),
            'category'     => trim((string) ($p['category'] ?? '')) !== '' ? trim((string) $p['category']) : null,
            'product_name' => trim((string) ($p['product_name'] ?? '')) !== '' ? trim((string) $p['product_name']) : null,
            'blurb'        => (string) ($p['blurb'] ?? ''),
            'href'         => trim((string) ($p['href'] ?? '')) !== '' ? trim((string) $p['href']) : null,
            'icon_color'   => trim((string) ($p['icon_color'] ?? '')) !== '' ? trim((string) $p['icon_color']) : null,
            'name_color'   => trim((string) ($p['name_color'] ?? '')) !== '' ? trim((string) $p['name_color']) : null,
            'icon_svg'     => trim((string) ($p['icon_svg'] ?? '')) !== '' ? trim((string) $p['icon_svg']) : null,
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

        foreach (['href', 'icon_color', 'name_color', 'category', 'product_name'] as $k) {
            $post[$k] = trim((string) ($post[$k] ?? ''));
        }

        return $post;
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        $kindList = implode(',', self::KINDS);

        return [
            'sort_order'   => 'permit_empty|integer',
            'kind'         => 'required|in_list[' . $kindList . ']',
            'title'        => 'permit_empty|max_length[255]',
            'description'  => 'permit_empty|max_length[65535]',
            'category'     => 'permit_empty|max_length[255]',
            'product_name' => 'permit_empty|max_length[255]',
            'blurb'        => 'permit_empty|max_length[65535]',
            'href'         => 'permit_empty|max_length[1024]',
            'icon_color'   => 'permit_empty|max_length[32]',
            'name_color'   => 'permit_empty|max_length[32]',
            'icon_svg'     => 'permit_empty|max_length[65535]',
        ];
    }

    private function syncTechStackItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'tech_stack_items';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
