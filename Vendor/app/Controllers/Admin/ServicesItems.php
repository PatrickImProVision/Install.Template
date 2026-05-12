<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServicesItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class ServicesItems extends BaseController
{
    private function servicesTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('services_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const KINDS = ['page_heading', 'service_card'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->servicesTableExists()) {
            return view('admin/services_items/schema_missing', ['title' => 'Services — setup needed']);
        }

        $items = model(ServicesItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/services_items/index', [
            'title' => 'Services — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->servicesTableExists()) {
            return view('admin/services_items/schema_missing', ['title' => 'Services — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/services_items/form', [
                    'title'   => 'New Services block',
                    'action'  => site_url('DashBoard/Services/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncServicesItemsIdSequenceForPostgres();

            $model = model(ServicesItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/services_items/form', [
                    'title'   => 'New Services block',
                    'action'  => site_url('DashBoard/Services/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Services'))->with('success', 'Block created.');
        }

        return view('admin/services_items/form', [
            'title'  => 'New Services block',
            'action' => site_url('DashBoard/Services/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->servicesTableExists()) {
            return view('admin/services_items/schema_missing', ['title' => 'Services — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(ServicesItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Services'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/services_items/form', [
                    'title'   => 'Edit Services block',
                    'action'  => site_url('DashBoard/Services/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(ServicesItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/services_items/form', [
                    'title'   => 'Edit Services block',
                    'action'  => site_url('DashBoard/Services/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Services'))->with('success', 'Block updated.');
        }

        return view('admin/services_items/form', [
            'title'  => 'Edit Services block',
            'action' => site_url('DashBoard/Services/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->servicesTableExists()) {
            return redirect()->to(site_url('DashBoard/Services'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/Services'));
        }

        model(ServicesItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/Services'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'          => null,
            'sort_order'  => 0,
            'kind'        => 'service_card',
            'title'       => '',
            'description' => '',
            'bullets'     => '',
            'image_url'   => '',
            'image_alt'   => '',
            'icon_svg'    => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizedPostRow(): array
    {
        $p = $this->request->getPost();

        return [
            'sort_order'  => (int) ($p['sort_order'] ?? 0),
            'kind'        => (string) ($p['kind'] ?? 'service_card'),
            'title'       => (string) ($p['title'] ?? ''),
            'description' => (string) ($p['description'] ?? ''),
            'bullets'     => (string) ($p['bullets'] ?? ''),
            'image_url'   => trim((string) ($p['image_url'] ?? '')) !== '' ? trim((string) $p['image_url']) : null,
            'image_alt'   => trim((string) ($p['image_alt'] ?? '')) !== '' ? trim((string) $p['image_alt']) : null,
            'icon_svg'    => trim((string) ($p['icon_svg'] ?? '')) !== '' ? trim((string) $p['icon_svg']) : null,
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

        $post['image_url'] = trim((string) ($post['image_url'] ?? ''));
        $post['image_alt'] = trim((string) ($post['image_alt'] ?? ''));

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
            'bullets'     => 'permit_empty|max_length[65535]',
            'image_url'   => 'permit_empty|max_length[1024]',
            'image_alt'   => 'permit_empty|max_length[255]',
            'icon_svg'    => 'permit_empty|max_length[65535]',
        ];
    }

    private function syncServicesItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'services_items';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
