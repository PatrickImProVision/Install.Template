<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingsModel;

class Dashboard extends BaseController
{
    private function siteSettingsTableExists(): bool
    {
        try {
            return \Config\Database::connect()->tableExists('site_settings');
        } catch (\Throwable) {
            return false;
        }
    }

    public function index(): string
    {
        helper('url');

        $settings = [
            'site_name'        => '',
            'site_description' => '',
        ];
        $siteSettingsSchemaMissing = true;

        if ($this->siteSettingsTableExists()) {
            try {
                $row = model(SiteSettingsModel::class)->find(1);
                if ($row !== null) {
                    $settings = $row;
                }
                $siteSettingsSchemaMissing = false;
            } catch (\Throwable) {
                // Table renamed/unreachable mid-request — keep safe defaults.
                $siteSettingsSchemaMissing = true;
            }
        }

        return view('admin/dashboard', [
            'title'                     => 'Dashboard',
            'settings'                  => $settings,
            'siteSettingsSchemaMissing' => $siteSettingsSchemaMissing,
        ]);
    }
}
