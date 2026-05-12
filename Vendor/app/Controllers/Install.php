<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Installer;
use App\Models\SiteSettingsModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Install extends BaseController
{
    protected $session;

    private const SESSION_DATABASE_DRAFT = 'install_database_draft';

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);
        $this->session = service('session');
    }

    public function index(): ResponseInterface
    {
        return redirect()->to(site_url('install/database'));
    }

    /** Plain-text probe: open …/install/health in the browser; if you do not see `install-health-ok`, routing/baseURL is wrong. */
    public function health(): ResponseInterface
    {
        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->setBody('install-health-ok');
    }

    public function database(): ResponseInterface|string
    {
        $this->response->noCache();

        if (! $this->request->is('post')) {
            return $this->respondDatabasePage(
                $this->mergeSessionDraftWithDefaults(),
                null,
                null,
                []
            );
        }

        try {
            // Empty $_POST happens when baseURL ≠ browser URL, post_max_size exceeded, or odd server setups.
            // Fall back to Request::getPost() and $_REQUEST so fields are not lost and connection test still runs.
            $postBag = $this->request->getPost();
            if (empty($_POST) && (empty($postBag) || $postBag === [])) {
                log_message('warning', '[Install::database] Empty POST body — check App.baseURL matches the address bar.');

                return $this->respondDatabasePage(
                    $this->mergeSessionDraftWithDefaults(),
                    null,
                    'The server received no form data (empty POST). Set app/Config/App.php baseURL to exactly match how you open this site (scheme, host, and path, with a trailing slash), then reload and try again.',
                    []
                );
            }

            $prefill = $this->readInstallDatabaseInput();
            $this->session->set(self::SESSION_DATABASE_DRAFT, $prefill);

            $driverList = implode(',', array_keys(Installer::DRIVER_LABELS));

            $rules = [
                'driver'   => 'required|in_list[' . $driverList . ']',
                'DBPrefix' => 'permit_empty|regex_match[/^[a-zA-Z0-9_]{0,64}$/]',
            ];

            $driver = $prefill['driver'];

            if ($driver === 'SQLite3') {
                $rules['database'] = 'required|max_length[1024]';
            } else {
                $rules['hostname'] = 'required|max_length[255]';
                $rules['port']     = 'required|integer|greater_than[0]|less_than_equal_to[65535]';
                $rules['username'] = 'required|max_length[255]';
                $rules['password'] = 'permit_empty|max_length[512]';
                $rules['database'] = 'required|max_length[128]';
            }

            if ($driver === 'Postgre' || $driver === 'SQLSRV') {
                $rules['schema'] = 'permit_empty|max_length[128]';
            }

            if (! $this->validateData($prefill, $rules)) {
                return $this->respondDatabasePage(
                    $prefill,
                    null,
                    null,
                    $this->validator->getErrors()
                );
            }

            $params = self::connectionParamsFromPrefill($prefill, $driver);

            try {
                Installer::testConnection($driver, $params);
            } catch (\Throwable $e) {
                return $this->respondDatabasePage(
                    $prefill,
                    null,
                    $e->getMessage(),
                    []
                );
            }

            // Which submit button was used (must read raw $_POST first — submitters are not always in getPost()).
            // If this is missing/empty, do NOT assume "save" or users get redirected with no on-page message.
            $submit = $this->readInstallDatabaseSubmitIntent();

            if ($submit !== 'save') {
                return $this->respondDatabasePage(
                    $prefill,
                    'Connection succeeded (' . $driver . '). You can save and continue.',
                    null,
                    []
                );
            }

            try {
                Installer::writeDatabaseConfig($driver, $params);
            } catch (\Throwable $e) {
                return $this->respondDatabasePage(
                    $prefill,
                    null,
                    $e->getMessage(),
                    []
                );
            }

            $this->session->remove(self::SESSION_DATABASE_DRAFT);
            $this->session->set('install_db_ok', true);
            $this->session->set('install_driver', $driver);

            return redirect()->to(site_url('install/schema'))->with('success', 'Database configuration saved.');
        } catch (\Throwable $e) {
            log_message('error', '[Install::database] ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return $this->respondDatabasePage(
                $this->mergeSessionDraftWithDefaults(),
                null,
                'Setup could not continue: ' . $e->getMessage(),
                []
            );
        }
    }

    public function schema(): ResponseInterface|string
    {
        if (! $this->session->get('install_db_ok')) {
            return redirect()->to(site_url('install/database'))->with('error', 'Complete the database step first.');
        }

        if (! $this->request->is('post')) {
            $driver = (string) ($this->session->get('install_driver') ?? '');
            if ($driver === '') {
                $driver = (string) (config(\Config\Database::class)->default['DBDriver'] ?? '');
            }

            return view('install/schema', [
                'title'           => 'Import database tables',
                'driver'          => $driver,
                'presetSqlHint'   => Installer::describePresetSqlPattern($driver),
            ]);
        }

        try {
            if (Installer::presetTablesAlreadyExist()) {
                $this->session->set('install_schema_ok', true);

                return redirect()->to(site_url('install/admin'))->with(
                    'success',
                    'Preset tables already exist in this database — import was not run again (duplicate submit). '
                    . 'You can continue with administrator setup.'
                );
            }

            Installer::importPresetTables();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $this->session->set('install_schema_ok', true);

        return redirect()->to(site_url('install/admin'))->with('success', 'Preset tables were imported.');
    }

    public function admin(): ResponseInterface|string
    {
        if (! $this->session->get('install_schema_ok')) {
            return redirect()->to(site_url('install/schema'))->with('error', 'Import the database tables first.');
        }

        if (! $this->request->is('post')) {
            return view('install/admin', [
                'title' => 'Administrator account',
            ]);
        }

        $rules = [
            'email'            => 'required|valid_email|max_length[255]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $userModel = model(UserModel::class);

        if ($userModel->where('email', $email)->first() !== null) {
            return redirect()->back()->withInput()->with(
                'error',
                'That email address is already registered. Use a different email address if you want to create another administrator account.'
            );
        }

        try {
            $insertId = $userModel->insert([
                'email'         => $email,
                'password_hash' => password_hash((string) $password, PASSWORD_DEFAULT),
                'role'          => 'administrator',
            ]);

            if ($insertId === false) {
                $dupMsg = 'That email address is already registered. Use a different email address if you want to create another administrator account.';

                return redirect()->back()->withInput()->with(
                    'error',
                    $this->installAdminInsertLooksLikeDuplicate($userModel) ? $dupMsg : 'Could not create the administrator. Please try again.'
                );
            }
        } catch (\Throwable $e) {
            if ($this->installAdminExceptionLooksLikeDuplicateEmail($e)) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'That email address is already registered. Use a different email address if you want to create another administrator account.'
                );
            }

            return redirect()->back()->withInput()->with('error', 'Could not create the administrator: ' . $e->getMessage());
        }

        $this->session->set('install_admin_ok', true);

        return redirect()->to(site_url('install/site'))->with('success', 'Administrator account created.');
    }

    public function site(): ResponseInterface|string
    {
        if (! $this->session->get('install_admin_ok')) {
            return redirect()->to(site_url('install/admin'))->with('error', 'Create the administrator account first.');
        }

        if (! $this->request->is('post')) {
            return view('install/site', [
                'title' => 'Site details',
            ]);
        }

        $rules = [
            'site_name'        => 'required|max_length[255]',
            'site_description' => 'permit_empty|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $settings = model(SiteSettingsModel::class);

        try {
            $settings->update(1, [
                'site_name'        => $this->request->getPost('site_name'),
                'site_description' => $this->request->getPost('site_description'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);
            Installer::writeInstalledFlag();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $this->session->remove(['install_db_ok', 'install_schema_ok', 'install_admin_ok', 'install_driver']);

        return redirect()->to(site_url('login'))->with('success', 'Installation finished. Sign in with your administrator email and password.');
    }

    /**
     * @param array<string, string> $prefill
     *
     * @return array{hostname:string,port:int,username:string,password:string,database:string,schema?:string,DBPrefix:string}
     */
    private static function connectionParamsFromPrefill(array $prefill, string $driver): array
    {
        $database = $prefill['database'] ?? '';
        $password = $prefill['password'] ?? '';
        $dbPrefix = Installer::normalizeDbPrefix($prefill['DBPrefix'] ?? '');

        if ($driver === 'SQLite3') {
            return [
                'hostname' => '',
                'port'     => 0,
                'username' => '',
                'password' => '',
                'database' => $database,
                'DBPrefix' => $dbPrefix,
            ];
        }

        $params = [
            'hostname' => $prefill['hostname'] ?? '',
            'port'     => (int) ($prefill['port'] ?? 3306),
            'username' => $prefill['username'] ?? '',
            'password' => $password,
            'database' => $database,
            'DBPrefix' => $dbPrefix,
        ];

        if ($driver === 'Postgre') {
            $schema = trim((string) ($prefill['schema'] ?? ''));
            $params['schema'] = $schema !== '' ? $schema : 'public';
        }

        if ($driver === 'SQLSRV') {
            $schema = trim((string) ($prefill['schema'] ?? ''));
            $params['schema'] = $schema !== '' ? $schema : 'dbo';
        }

        return $params;
    }

    /**
     * @return array{driver:string,hostname:string,port:string,username:string,password:string,database:string,schema:string,DBPrefix:string}
     */
    private function databaseFormDefaults(): array
    {
        return [
            'driver'   => 'MySQLi',
            'hostname' => 'localhost',
            'port'     => '3306',
            'username' => '',
            'password' => '',
            'database' => '',
            'schema'   => '',
            'DBPrefix' => '',
        ];
    }

    /**
     * GET: restore saved installer draft from session (survives refresh / cache quirks).
     *
     * @return array{driver:string,hostname:string,port:string,username:string,password:string,database:string,schema:string,DBPrefix:string}
     */
    private function mergeSessionDraftWithDefaults(): array
    {
        $row = $this->databaseFormDefaults();
        $stored = $this->session->get(self::SESSION_DATABASE_DRAFT);
        if (! is_array($stored)) {
            return $row;
        }

        foreach (array_keys($row) as $key) {
            if (! array_key_exists($key, $stored)) {
                continue;
            }
            $v = $stored[$key];
            $row[$key] = is_scalar($v) ? (string) $v : '';
        }

        return $row;
    }

    /**
     * POST submit intent for the database step: "test" or "save".
     * Prefer install_btn; accept legacy name="action" for older cached HTML.
     */
    private function readInstallDatabaseSubmitIntent(): string
    {
        $candidates = [
            $_POST['install_btn'] ?? null,
            $_POST['action'] ?? null,
            $this->request->getPost('install_btn'),
            $this->request->getPost('action'),
        ];

        foreach ($candidates as $raw) {
            if ($raw === null || $raw === '') {
                continue;
            }

            $v = is_scalar($raw) ? trim((string) $raw) : '';

            return match ($v) {
                'save', 'test' => $v,
                default        => 'test',
            };
        }

        return 'test';
    }

    /**
     * POST: defaults + session, then overlay each field from $_POST, Request::getPost(), or $_REQUEST (in that order).
     *
     * @return array{driver:string,hostname:string,port:string,username:string,password:string,database:string,schema:string,DBPrefix:string}
     */
    private function readInstallDatabaseInput(): array
    {
        $row = $this->mergeSessionDraftWithDefaults();

        foreach (array_keys($this->databaseFormDefaults()) as $key) {
            $raw = null;

            if (array_key_exists($key, $_POST)) {
                $raw = $_POST[$key];
            } elseif ($this->request->getPost($key) !== null) {
                $raw = $this->request->getPost($key);
            } elseif (isset($_REQUEST[$key]) && ! is_array($_REQUEST[$key])) {
                $raw = $_REQUEST[$key];
            }

            if ($raw === null) {
                continue;
            }

            if ($key === 'password') {
                $row[$key] = is_scalar($raw) ? (string) $raw : '';

                continue;
            }

            $row[$key] = is_scalar($raw) ? trim((string) $raw) : '';
        }

        return $row;
    }

    /**
     * Explicit Response body + headers (avoids rare cases where returning a string from the controller drops HTML).
     *
     * @param list<string>|array<string, string> $fieldErrors
     */
    private function respondDatabasePage(array $prefill, ?string $success, ?string $error, array $fieldErrors): ResponseInterface
    {
        $html = $this->renderDatabaseForm($prefill, $success, $error, $fieldErrors);

        $tag = 'none';
        if (($success ?? '') !== '') {
            $tag = 'ok';
        } elseif (($error ?? '') !== '') {
            $tag = 'err';
        } elseif ($fieldErrors !== []) {
            $tag = 'val';
        }

        log_message('notice', '[Install::database] HTML response install-db=' . $tag);

        return $this->response->setBody($html)->setHeader('X-Install-DB', $tag);
    }

    /**
     * @param list<string>|array<string, string> $fieldErrors
     */
    private function renderDatabaseForm(array $prefill, ?string $success, ?string $error, array $fieldErrors): string
    {
        $prefill = array_merge($this->databaseFormDefaults(), $prefill);

        // Bypass CodeIgniter's View renderer (singleton / saveData can drop variables). Plain include + extract scope.
        return (function () use ($prefill, $success, $error, $fieldErrors): string {
            $title         = 'Database connection';
            $driverChoices = Installer::DRIVER_LABELS;
            $dbFeedback    = [
                'success' => $success ?? '',
                'error'   => $error ?? '',
                'errors'  => $fieldErrors,
            ];

            ob_start();
            include APPPATH . 'Views/install/database.php';

            return ob_get_clean() ?: '';
        })();
    }

    private function installAdminInsertLooksLikeDuplicate(UserModel $model): bool
    {
        foreach ($model->errors() as $msg) {
            $m = strtolower((string) $msg);
            if (str_contains($m, 'duplicate') || str_contains($m, 'unique') || str_contains($m, 'already exists')) {
                return true;
            }
        }

        return false;
    }

    private function installAdminExceptionLooksLikeDuplicateEmail(\Throwable $e): bool
    {
        $msg = strtolower($e->getMessage());

        foreach (['duplicate', 'unique constraint', 'unique violation', '23000', 'already exists'] as $needle) {
            if (str_contains($msg, $needle)) {
                return true;
            }
        }

        return false;
    }
}
