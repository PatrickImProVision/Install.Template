<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingsModel;
use CodeIgniter\HTTP\ResponseInterface;

class SiteSettings extends BaseController
{
    private const SETTINGS_ID = 1;

    private function siteSettingsTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('site_settings');
        } catch (\Throwable) {
            return false;
        }
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
    }

    public function index(): string
    {
        if (! $this->siteSettingsTableExists()) {
            return view('admin/site_settings/schema_missing', ['title' => 'Site settings — setup needed']);
        }

        return $this->renderSettingsIndex($this->request->getGet('saved') === '1');
    }

    /**
     * Shared list view for GET DashBoard/Web_Settings (banner from ?saved=1 and/or flash success).
     */
    private function renderSettingsIndex(bool $savedBanner): string
    {
        $this->ensureSettingsRow();

        $settings = model(SiteSettingsModel::class)->find(self::SETTINGS_ID) ?? [
            'site_name'        => '',
            'site_description' => '',
        ];

        return view('admin/site_settings/index', [
            'title'       => 'Site settings',
            'settings'    => $settings,
            'savedBanner' => $savedBanner,
        ]);
    }

    public function edit(?string $id = null): ResponseInterface|string
    {
        if ((int) ($id ?? 0) !== self::SETTINGS_ID) {
            return redirect()->to(site_url('DashBoard/Web_Settings'));
        }

        if (! $this->siteSettingsTableExists()) {
            return view('admin/site_settings/schema_missing', ['title' => 'Site settings — setup needed']);
        }

        $this->ensureSettingsRow();

        $model = model(SiteSettingsModel::class);
        $row   = $model->find(self::SETTINGS_ID);

        if ($row === null) {
            return redirect()->to(site_url('DashBoard/Web_Settings'));
        }

        if ($this->request->is('post')) {
            $rules = [
                'site_name'        => 'permit_empty|max_length[255]',
                'site_description' => 'permit_empty|max_length[65535]',
            ];

            if (! $this->validateData($this->request->getPost(), $rules)) {
                return view('admin/site_settings/form', [
                    'title'   => 'Edit site settings',
                    'action'  => site_url('DashBoard/Web_Settings/Edit/' . self::SETTINGS_ID),
                    'record'  => array_merge($row, [
                        'site_name'        => (string) $this->request->getPost('site_name'),
                        'site_description' => (string) $this->request->getPost('site_description'),
                    ]),
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            $ok = $model->update(self::SETTINGS_ID, [
                'site_name'        => (string) $this->request->getPost('site_name'),
                'site_description' => (string) $this->request->getPost('site_description'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);

            if ($ok === false) {
                return view('admin/site_settings/form', [
                    'title'   => 'Edit site settings',
                    'action'  => site_url('DashBoard/Web_Settings/Edit/' . self::SETTINGS_ID),
                    'record'  => array_merge($row, [
                        'site_name'        => (string) $this->request->getPost('site_name'),
                        'site_description' => (string) $this->request->getPost('site_description'),
                    ]),
                    'errors'  => ['_form' => 'Could not save settings. Check the database connection and try again.'],
                ]);
            }

            // POST → redirect → GET (PRG); 303 is chosen automatically for POST redirects.
            $listUrl = site_url('DashBoard/Web_Settings');
            $listUrl .= str_contains($listUrl, '?') ? '&saved=1' : '?saved=1';

            return redirect()->to($listUrl)->with('success', 'Site settings saved.');
        }

        return view('admin/site_settings/form', [
            'title'  => 'Edit site settings',
            'action' => site_url('DashBoard/Web_Settings/Edit/' . self::SETTINGS_ID),
            'record' => $row,
            'errors' => [],
        ]);
    }

    private function ensureSettingsRow(): void
    {
        $model = model(SiteSettingsModel::class);

        if ($model->find(self::SETTINGS_ID) !== null) {
            return;
        }

        try {
            $model->insert([
                'id'                 => self::SETTINGS_ID,
                'site_name'          => '',
                'site_description'   => '',
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable) {
            // Row may already exist from a concurrent request or DB constraint.
        }
    }
}
