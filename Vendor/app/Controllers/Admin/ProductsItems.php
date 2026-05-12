<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductsItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProductsItems extends BaseController
{
    private function productsTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('products_items');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @var list<string> */
    public const KINDS = ['page_heading', 'product_card'];

    /** @var list<string> */
    public const CARD_STYLES = ['grad-blue', 'grad-cyan', 'grad-emerald', 'grad-purple'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->productsTableExists()) {
            return view('admin/products_items/schema_missing', ['title' => 'Products & services — setup needed']);
        }

        $items = model(ProductsItemModel::class)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/products_items/index', [
            'title' => 'Products & services — blocks',
            'items' => $items,
        ]);
    }

    public function create(): ResponseInterface|string
    {
        if (! $this->productsTableExists()) {
            return view('admin/products_items/schema_missing', ['title' => 'Products & services — setup needed']);
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/products_items/form', [
                    'title'   => 'New Products block',
                    'action'  => site_url('DashBoard/Products/Create'),
                    'record'  => $this->normalizedPostRow(),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $this->syncProductsItemsIdSequenceForPostgres();

            $model = model(ProductsItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->insert($data)) {
                return view('admin/products_items/form', [
                    'title'   => 'New Products block',
                    'action'  => site_url('DashBoard/Products/Create'),
                    'record'  => $data,
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not save the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Products'))->with('success', 'Block created.');
        }

        return view('admin/products_items/form', [
            'title'  => 'New Products block',
            'action' => site_url('DashBoard/Products/Create'),
            'record' => $this->emptyRow(),
            'errors' => [],
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if (! $this->productsTableExists()) {
            return view('admin/products_items/schema_missing', ['title' => 'Products & services — setup needed']);
        }

        $id = (int) ($id ?? 0);
        $row = model(ProductsItemModel::class)->find($id);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Products'))->with('success', 'That block was not found.');
        }

        if ($this->request->is('post')) {
            $rules = $this->rules();

            if (! $this->validateData($this->validationPost(), $rules)) {
                return view('admin/products_items/form', [
                    'title'   => 'Edit Products block',
                    'action'  => site_url('DashBoard/Products/Edit/' . $id),
                    'record'  => array_merge($row, $this->normalizedPostRow()),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $model = model(ProductsItemModel::class);
            $data  = $this->normalizedPostRow();

            if (! $model->update($id, $data)) {
                return view('admin/products_items/form', [
                    'title'   => 'Edit Products block',
                    'action'  => site_url('DashBoard/Products/Edit/' . $id),
                    'record'  => array_merge($row, $data),
                    'errors'  => array_merge($model->errors(), [
                        '_form' => 'Could not update the block. Check the database error or try again.',
                    ]),
                ]);
            }

            return redirect()->to(site_url('DashBoard/Products'))->with('success', 'Block updated.');
        }

        return view('admin/products_items/form', [
            'title'  => 'Edit Products block',
            'action' => site_url('DashBoard/Products/Edit/' . $id),
            'record' => $row,
            'errors' => [],
        ]);
    }

    public function delete(?string $id = null): ResponseInterface
    {
        if (! $this->productsTableExists()) {
            return redirect()->to(site_url('DashBoard/Products'));
        }

        $id = (int) ($id ?? 0);

        if (! $this->request->is('post')) {
            return redirect()->to(site_url('DashBoard/Products'));
        }

        model(ProductsItemModel::class)->delete($id);

        return redirect()->to(site_url('DashBoard/Products'))->with('success', 'Block deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyRow(): array
    {
        return [
            'id'           => null,
            'sort_order'   => 0,
            'kind'         => 'product_card',
            'title'        => '',
            'description'  => '',
            'bullets'      => '',
            'sub_line'     => '',
            'href'         => '',
            'card_style'   => 'grad-blue',
            'icon_svg'     => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizedPostRow(): array
    {
        $p = $this->request->getPost();

        $kind  = (string) ($p['kind'] ?? 'product_card');
        $style = trim((string) ($p['card_style'] ?? ''));
        if ($style === '' || ! in_array($style, self::CARD_STYLES, true)) {
            $style = 'grad-blue';
        }

        return [
            'sort_order'    => (int) ($p['sort_order'] ?? 0),
            'kind'          => $kind,
            'title'         => (string) ($p['title'] ?? ''),
            'description'   => (string) ($p['description'] ?? ''),
            'bullets'       => (string) ($p['bullets'] ?? ''),
            'sub_line'      => trim((string) ($p['sub_line'] ?? '')) !== '' ? trim((string) $p['sub_line']) : null,
            'href'          => trim((string) ($p['href'] ?? '')) !== '' ? trim((string) $p['href']) : null,
            'card_style'    => $kind === 'product_card' ? $style : null,
            'icon_svg'      => trim((string) ($p['icon_svg'] ?? '')) !== '' ? trim((string) $p['icon_svg']) : null,
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

        foreach (['sub_line', 'href', 'card_style'] as $k) {
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
        $styleList = implode(',', self::CARD_STYLES);

        return [
            'sort_order'   => 'permit_empty|integer',
            'kind'         => 'required|in_list[' . $kindList . ']',
            'title'        => 'permit_empty|max_length[255]',
            'description'  => 'permit_empty|max_length[65535]',
            'bullets'      => 'permit_empty|max_length[65535]',
            'sub_line'     => 'permit_empty|max_length[255]',
            'href'         => 'permit_empty|max_length[1024]',
            'card_style'   => 'permit_empty|in_list[' . $styleList . ']',
            'icon_svg'     => 'permit_empty|max_length[65535]',
        ];
    }

    private function syncProductsItemsIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'products_items';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
