<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('url');

        $session = session();
        if (! $session->get('user_id')) {
            return redirect()->to(site_url('login'))->with('error', 'Please sign in to continue.');
        }

        if (($session->get('user_role') ?? '') !== 'administrator') {
            return redirect()->to(site_url('/'))->with('error', 'Administrator access is required.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
