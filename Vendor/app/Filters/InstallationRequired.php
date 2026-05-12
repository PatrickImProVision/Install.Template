<?php

declare(strict_types=1);

namespace App\Filters;

use App\Libraries\Installer;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Apply only to routes **outside** `install/*`.
 *
 * Until `.installed` exists, every non-install URL redirects to the database wizard so POST bodies are never
 * mangled by "is this the installer?" heuristics on install routes.
 */
class InstallationRequired implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (Installer::isInstalled()) {
            return null;
        }

        helper('url');

        return redirect()->to(site_url('install/database'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
