<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AboutUsItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class AboutUs extends BaseController
{
    private function aboutTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('about_us_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const PLACEMENTS = ['page_header', 'intro', 'stack'];

    /** @var list<string> */
    public const KINDS = ['page_heading', 'mission', 'badge', 'company_card'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->aboutTableExists()) {
            return view('admin/about_us/schema_missing', ['title' => 'About us — setup needed']);
        }

        $items = model(AboutUsItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/about_us/index', [
            'title' => 'About us — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->aboutTableExists()) {
            return view('admin/about_us/schema_missing', ['title' => 'About us — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/about_us/form', [
                    'title'   => 'New About block',
                    'action'  => site_url('DashBoard/About_Us/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncAboutUsItemsIdSequenceForPostgres();

            $model = model(AboutUsItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/about_us/form', [
                    'title'   => 'New About block',
                    'action'  => site_url('DashBoard/About_Us/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/About_Us'))->with('success', 'Block created.');
        }

        return view('admin/about_us/form', [
            'title'  => 'New About block',
            'action' => site_url('DashBoard/About_Us/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->aboutTableExists()) {
            return view('admin/about_us/schema_missing', ['title' => 'About us — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(AboutUsItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/About_Us'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/about_us/form', [
                    'title'   => 'Edit About block',
                    'action'  => site_url('DashBoard/About_Us/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(AboutUsItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/about_us/form', [
                    'title'   => 'Edit About block',
                    'action'  => site_url('DashBoard/About_Us/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/About_Us'))->with('success', 'Block updated.');
        }

        return view('admin/about_us/form', [
            'title'  => 'Edit About block',
            'action' => site_url('DashBoard/About_Us/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->aboutTableExists()) {
            return redirect()->to(site_url('DashBoard/About_Us'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/About_Us'));
        }

        model(AboutUsItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/About_Us'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'           => null,
            'sort_order'   => 0,
            'placement'    => 'stack',
            'kind'         => 'company_card',
            'title'        => '',
            'description'  => '',
            'href'         => '',
            'icon_key'     => '',
            'bullets'      => '',
            'footnote'     => '',
            'card_style'   => '',
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
            'placement'   => (string) ($p['placement'] ?? 'stack'),
            'kind'        => (string) ($p['kind'] ?? 'company_card'),
            'title'       => (string) ($p['title'] ?? ''),
            'description' => (string) ($p['description'] ?? ''),
            'href'        => trim((string) ($p['href'] ?? '')) !== '' ? (string) $p['href'] : null,
            'icon_key'    => trim((string) ($p['icon_key'] ?? '')) !== '' ? (string) $p['icon_key'] : null,
            'bullets'     => (string) ($p['bullets'] ?? ''),
            'footnote'    => trim((string) ($p['footnote'] ?? '')) !== '' ? (string) $p['footnote'] : null,
            'card_style'  => trim((string) ($p['card_style'] ?? '')),
        ];
    }

    /**
     * POST fields normalized before validation (same trimming as {@see normalizedPostRow()}).
     *
     * @return array<string, mixed>
     */
    private function validationPost(): array
    {
        $post = $this->request->getPost();
        if (! is_array($post)) {
            return [];
        }

        $post['card_style'] = trim((string) ($post['card_style'] ?? ''));

        return $post;
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        $placementList = implode(',', self::PLACEMENTS);
        $kindList      = implode(',', self::KINDS);

        return [
            'sort_order'  => 'permit_empty|integer',
            'placement'   => 'required|in_list[' . $placementList . ']',
            'kind'        => 'required|in_list[' . $kindList . ']',
            'title'       => 'permit_empty|max_length[255]',
            'description' => 'permit_empty|max_length[65535]',
            'href'        => 'permit_empty|max_length[512]',
            'icon_key'    => 'permit_empty|max_length[64]',
            'bullets'     => 'permit_empty|max_length[65535]',
            'footnote'    => 'permit_empty|max_length[512]',
            // Pattern must start with "/" so FormatRules::regex_match does not wrap again (hash-delimited patterns break).
            'card_style'  => 'permit_empty|regex_match[/^(blue|amber)?$/]',
        ];
    }

    /**
     * After seed rows with explicit ids, PostgreSQL's sequence may still be low and the next INSERT can fail
     * with a duplicate key on id. Align the sequence to MAX(id) before inserting a new row.
     */
    private function syncAboutUsItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'about_us_items';
            $seq   = $table . '_id_seq';
            // regclass literal — binding sequence names is unreliable across drivers.
            $sql = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
            // Non-fatal; insert may still succeed if sequence was already correct.
        }
    }
}
