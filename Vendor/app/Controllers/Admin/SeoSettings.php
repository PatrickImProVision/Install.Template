<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SeoPageModel;
use CodeIgniter\HTTP\ResponseInterface;

class SeoSettings extends BaseController
{
    /** @var array<string, string> */
    public const PAGE_LABELS = [
        'home'       => 'Home',
        'about-us'   => 'About us',
        'services'   => 'Services',
        'products'   => 'Products',
        'tech-stack' => 'Technology stack',
        'values'     => 'Our values',
        'contact'    => 'Contact',
    ];

    private function seoTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('seo_pages');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return list<string> */
    public static function pageKeys(): array
    {
        return array_keys(self::PAGE_LABELS);
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->seoTableExists()) {
            return view('admin/seo_settings/schema_missing', ['title' => 'SEO settings — setup needed']);
        }

        $model = model(SeoPageModel::class);
        $rows  = [];
        try {
            $rows = $model->orderBy('page_key', 'ASC')->findAll();
        } catch (\Throwable) {
            $rows = [];
        }

        $byKey = [];
        foreach ($rows as $r) {
            $byKey[(string) ($r['page_key'] ?? '')] = $r;
        }

        return view('admin/seo_settings/index', [
            'title'   => 'SEO — page meta',
            'labels'  => self::PAGE_LABELS,
            'rowsByKey' => $byKey,
        ]);
    }

    public function edit(?string $pageKey = null): ResponseInterface|string
    {
        if (! $this->seoTableExists()) {
            return view('admin/seo_settings/schema_missing', ['title' => 'SEO settings — setup needed']);
        }

        $pageKey = trim((string) ($pageKey ?? ''));
        if ($pageKey === '' || ! array_key_exists($pageKey, self::PAGE_LABELS)) {
            return redirect()->to(site_url('DashBoard/SEO_Settings'));
        }

        $model = model(SeoPageModel::class);
        $row   = $model->where('page_key', $pageKey)->first();

        if ($row === null) {
            $row = [
                'id'               => null,
                'page_key'         => $pageKey,
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
            ];
        }

        if ($this->request->is('post')) {
            $rules = [
                'meta_title'       => 'permit_empty|max_length[255]',
                'meta_description' => 'permit_empty|max_length[65535]',
                'meta_keywords'    => 'permit_empty|max_length[512]',
            ];

            $post = [
                'meta_title'       => (string) $this->request->getPost('meta_title'),
                'meta_description' => (string) $this->request->getPost('meta_description'),
                'meta_keywords'    => (string) $this->request->getPost('meta_keywords'),
            ];

            if (! $this->validateData($post, $rules)) {
                return view('admin/seo_settings/form', [
                    'title'        => 'Edit SEO — ' . self::PAGE_LABELS[$pageKey],
                    'pageLabel'    => self::PAGE_LABELS[$pageKey],
                    'pageKey'      => $pageKey,
                    'action'       => site_url('DashBoard/SEO_Settings/Edit/' . rawurlencode($pageKey)),
                    'record'       => array_merge($row, $post),
                    'errors'       => $this->validator->getErrors(),
                ]);
            }

            $data = [
                'meta_title'       => $post['meta_title'],
                'meta_description' => $post['meta_description'],
                'meta_keywords'    => $post['meta_keywords'],
            ];

            $ok = false;

            if (! empty($row['id'])) {
                $ok = $model->update((int) $row['id'], $data);
            } else {
                $data['page_key'] = $pageKey;
                $ok               = $model->insert($data) !== false;
            }

            if ($ok) {
                $this->syncSeoPagesIdSequenceForPostgres();
            }

            if (! $ok) {
                return view('admin/seo_settings/form', [
                    'title'        => 'Edit SEO — ' . self::PAGE_LABELS[$pageKey],
                    'pageLabel'    => self::PAGE_LABELS[$pageKey],
                    'pageKey'      => $pageKey,
                    'action'       => site_url('DashBoard/SEO_Settings/Edit/' . rawurlencode($pageKey)),
                    'record'       => array_merge($row, $data),
                    'errors'       => ['_form' => 'Could not save. Check the database error or try again.'],
                ]);
            }

            return redirect()->to(site_url('DashBoard/SEO_Settings'))->with('success', 'SEO saved for ' . self::PAGE_LABELS[$pageKey] . '.');
        }

        return view('admin/seo_settings/form', [
            'title'     => 'Edit SEO — ' . self::PAGE_LABELS[$pageKey],
            'pageLabel' => self::PAGE_LABELS[$pageKey],
            'pageKey'   => $pageKey,
            'action'    => site_url('DashBoard/SEO_Settings/Edit/' . rawurlencode($pageKey)),
            'record'    => $row,
            'errors'    => [],
        ]);
    }

    private function syncSeoPagesIdSequenceForPostgres(): void
    {
        try {
            $db = \Config\Database::connect();
            if (($db->DBDriver ?? '') !== 'Postgre') {
                return;
            }

            $table = $db->DBPrefix . 'seo_pages';
            $seq   = $table . '_id_seq';
            $sql   = 'SELECT setval(\'' . str_replace('\'', '\'\'', $seq) . '\'::regclass, COALESCE((SELECT MAX(id) FROM '
                . $db->escapeIdentifiers($table) . '), 1))';
            $db->query($sql);
        } catch (\Throwable) {
        }
    }
}
