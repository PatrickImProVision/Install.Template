<?php

declare(strict_types=1);

namespace App\Filters;

use App\Libraries\InstallationState;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Forces the installer when the app is not installed; limits install routes once installed.
 */
final class InstallGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): ?ResponseInterface
    {
        // Do not use getSegment(2) on short paths (e.g. "/") — it throws HTTPException when out of range.
        $segments = $request->getUri()->getSegments();
        $seg1     = $segments[0] ?? '';
        $seg2     = $segments[1] ?? '';

        $onInstaller = ($seg1 === 'install');
        $installed   = InstallationState::isInstalled();

        if (! $installed && ! $onInstaller) {
            return redirect()->to(site_url('install'));
        }

        if ($installed && $onInstaller) {
            if ($seg2 === 'uninstall') {
                $seg3 = $segments[2] ?? '';
                if (! in_array($seg3, ['', 'confirm', 'next'], true)) {
                    return redirect()->to('/');
                }
            } else {
                return redirect()->to('/');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
