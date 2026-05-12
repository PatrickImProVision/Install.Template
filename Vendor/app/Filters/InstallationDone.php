<?php

declare(strict_types=1);

namespace App\Filters;

use App\Libraries\Installer;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Apply only to `install/*` routes.
 *
 * After installation, the wizard URLs redirect home.
 */
class InstallationDone implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! Installer::isInstalled()) {
            return null;
        }

        helper('url');

        return redirect()->to(site_url('/'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
