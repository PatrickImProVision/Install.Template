<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $session;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->session = service('session');
    }

    public function login(): ResponseInterface|string
    {
        if ($this->session->get('user_id')) {
            return redirect()->to(site_url('/'));
        }

        if (! $this->request->is('post')) {
            return view('auth/login', ['title' => 'Sign in']);
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = model(UserModel::class);
        $user      = $userModel->where('email', $email)->first();

        if ($user === null || ! password_verify((string) $password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        $this->session->set([
            'user_id'    => $user['id'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
        ]);

        return redirect()->to(site_url('/'))->with('success', 'Welcome back.');
    }

    public function logout(): ResponseInterface
    {
        $this->session->destroy();

        return redirect()->to(site_url('/'))->with('success', 'You have been signed out.');
    }
}
