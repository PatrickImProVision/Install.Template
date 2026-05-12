<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class ContactItems extends BaseController
{
    private function contactTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('contact_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const KINDS = ['page_heading', 'brand', 'column_heading', 'company_entry', 'contact_entry', 'legal'];

    /** @var list<string> */
    public const COLUMN_GROUPS = ['page', 'intro', 'company', 'contact', 'legal'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->contactTableExists()) {
            return view('admin/contact_items/schema_missing', ['title' => 'Contact & footer — setup needed']);
        }

        $items = model(ContactItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/contact_items/index', [
            'title' => 'Contact & footer — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->contactTableExists()) {
            return view('admin/contact_items/schema_missing', ['title' => 'Contact & footer — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/contact_items/form', [
                    'title'   => 'New Contact block',
                    'action'  => site_url('DashBoard/Site_Contact/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncContactItemsIdSequenceForPostgres();

            $model = model(ContactItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/contact_items/form', [
                    'title'   => 'New Contact block',
                    'action'  => site_url('DashBoard/Site_Contact/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Site_Contacts'))->with('success', 'Block created.');
        }

        return view('admin/contact_items/form', [
            'title'  => 'New Contact block',
            'action' => site_url('DashBoard/Site_Contact/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->contactTableExists()) {
            return view('admin/contact_items/schema_missing', ['title' => 'Contact & footer — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(ContactItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Site_Contacts'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/contact_items/form', [
                    'title'   => 'Edit Contact block',
                    'action'  => site_url('DashBoard/Site_Contact/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(ContactItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/contact_items/form', [
                    'title'   => 'Edit Contact block',
                    'action'  => site_url('DashBoard/Site_Contact/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Site_Contacts'))->with('success', 'Block updated.');
        }

        return view('admin/contact_items/form', [
            'title'  => 'Edit Contact block',
            'action' => site_url('DashBoard/Site_Contact/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->contactTableExists()) {
            return redirect()->to(site_url('DashBoard/Site_Contacts'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/Site_Contacts'));
        }

        model(ContactItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/Site_Contacts'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'            => null,
            'sort_order'    => 0,
            'kind'          => 'contact_entry',
            'column_group'  => 'contact',
            'title'         => '',
            'description'   => '',
            'meta_label'    => '',
            'href'          => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizedPostRow(): array
    {
        $p       = $this->request->getPost();
        $kind    = (string) ($p['kind'] ?? 'contact_entry');
        $cgPost  = trim((string) ($p['column_group'] ?? ''));
        $href    = trim((string) ($p['href'] ?? '')) !== '' ? trim((string) $p['href']) : null;
        $meta    = trim((string) ($p['meta_label'] ?? '')) !== '' ? trim((string) $p['meta_label']) : null;

        $columnGroup = match ($kind) {
            'page_heading' => 'page',
            'brand' => 'intro',
            'company_entry' => 'company',
            'contact_entry' => 'contact',
            'legal' => 'legal',
            'column_heading' => in_array($cgPost, ['company', 'contact'], true) ? $cgPost : 'company',
            default => in_array($cgPost, self::COLUMN_GROUPS, true) ? $cgPost : 'contact',
        };

        return [
            'sort_order'    => (int) ($p['sort_order'] ?? 0),
            'kind'          => $kind,
            'column_group'  => $columnGroup,
            'title'         => (string) ($p['title'] ?? ''),
            'description'   => (string) ($p['description'] ?? ''),
            'meta_label'    => $meta,
            'href'          => $href,
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

        foreach (['href', 'meta_label', 'column_group'] as $k) {
            $post[$k] = trim((string) ($post[$k] ?? ''));
        }

        return $post;
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        $kindList  = implode(',', self::KINDS);
        $groupList = implode(',', self::COLUMN_GROUPS);

        return [
            'sort_order'    => 'permit_empty|integer',
            'kind'          => 'required|in_list[' . $kindList . ']',
            'column_group'  => 'permit_empty|in_list[' . $groupList . ']',
            'title'         => 'permit_empty|max_length[255]',
            'description'   => 'permit_empty|max_length[65535]',
            'meta_label'    => 'permit_empty|max_length[255]',
            'href'          => 'permit_empty|max_length[1024]',
        ];
    }

    private function syncContactItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'contact_items';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
