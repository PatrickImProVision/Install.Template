<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'System status') ?></title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            background: #f4f6f9;
            color: #1a1a1a;
            line-height: 1.5;
        }
        header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        header h1 { font-size: 1.1rem; margin: 0; }
        main { max-width: 920px; margin: 0 auto; padding: 2rem 1.25rem; }
        .panel {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
        }
        .panel h2 { margin: 0 0 .75rem; font-size: 1rem; }
        .muted { color: #64748b; font-size: .85rem; margin: 0 0 .5rem; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        th, td { text-align: left; padding: .45rem .6rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { font-weight: 600; color: #475569; font-size: .78rem; text-transform: uppercase; letter-spacing: .02em; }
        code { font-size: .82rem; background: #f1f5f9; padding: .1rem .35rem; border-radius: 4px; word-break: break-all; }
        .ok { color: #059669; font-weight: 600; }
        .bad { color: #b91c1c; font-weight: 600; }
        .warn { color: #b45309; font-weight: 600; }
        dl { margin: 0; display: grid; grid-template-columns: 11rem 1fr; gap: .35rem .75rem; font-size: .9rem; }
        dt { color: #64748b; }
        dd { margin: 0; }
    </style>
</head>
<body>
<?php
$db       = $database ?? [];
$writable = $writable ?? [];
$install  = $installFlag ?? ['present' => false, 'path' => '', 'readable' => false];
$exts     = $extensions ?? [];

$presetOk = ! empty($db['connected'])
    && isset($db['tables'])
    && count($db['tables']) > 0
    && array_reduce(
        $db['tables'],
        static fn (bool $carry, array $t): bool => $carry && ! empty($t['exists']),
        true
    );
$writeOk = array_reduce(
    $writable,
    static fn (bool $carry, array $w): bool => $carry && ! empty($w['ok']),
    true
);
$overallOk = ! empty($install['present']) && $presetOk && $writeOk && empty($db['error']);
?>
<header>
    <h1><?= esc($title ?? 'System status') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">SEO</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('/')) ?>">Home</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('logout')) ?>">Sign out</a>
    </nav>
</header>
<main>
    <div class="panel">
        <h2>Overall</h2>
        <p class="muted" style="margin-top:0">
            <?php if ($overallOk) : ?>
                <span class="ok">All checked components report healthy.</span>
            <?php else : ?>
                <span class="bad">Some checks failed — review the sections below.</span>
            <?php endif; ?>
        </p>
        <dl>
            <dt>Install flag</dt>
            <dd><?= ! empty($install['present']) ? '<span class="ok">Present</span>' : '<span class="bad">Missing</span>' ?> <code><?= esc($install['path'] ?? '') ?></code></dd>
            <dt>Preset tables</dt>
            <dd><?= $presetOk ? '<span class="ok">All present</span>' : '<span class="bad">Incomplete or DB error</span>' ?></dd>
            <dt>Writable paths</dt>
            <dd><?= $writeOk ? '<span class="ok">OK</span>' : '<span class="bad">Issues</span>' ?></dd>
        </dl>
    </div>

    <div class="panel">
        <h2>Application &amp; PHP</h2>
        <dl>
            <dt>Environment</dt>
            <dd><code><?= esc($environment ?? '') ?></code></dd>
            <dt>Base URL</dt>
            <dd><code><?= esc($baseURL ?? '') ?></code></dd>
            <dt>CodeIgniter</dt>
            <dd><?= esc($ciVersion ?? '') ?></dd>
            <dt>PHP</dt>
            <dd><?= esc($phpVersion ?? '') ?> (<code><?= esc($phpSapi ?? '') ?></code>)</dd>
            <dt>Timezone</dt>
            <dd><?= esc($timezone ?? '') ?></dd>
            <dt>Server</dt>
            <dd><?= esc($serverSoftware !== '' ? $serverSoftware : '—') ?></dd>
            <dt>Memory limit</dt>
            <dd><?= esc($memoryLimit ?? '') ?></dd>
            <dt>post_max_size</dt>
            <dd><?= esc($postMaxSize ?? '') ?></dd>
            <dt>upload_max_filesize</dt>
            <dd><?= esc($uploadMaxSize ?? '') ?></dd>
            <?php if (! empty($sessionUser)) : ?>
                <dt>Signed in as</dt>
                <dd><?= esc((string) $sessionUser) ?></dd>
            <?php endif; ?>
            <?php if ($diskFreeBytes !== false && $diskFreeBytes !== null) : ?>
                <dt>Free space (writable)</dt>
                <dd><?= esc(number_format((float) $diskFreeBytes)) ?> bytes</dd>
            <?php endif; ?>
        </dl>
    </div>

    <div class="panel">
        <h2>Install flag file</h2>
        <p class="muted" style="margin-top:0">Created when setup completes. Without it, public routes redirect to the installer.</p>
        <dl>
            <dt>Path</dt>
            <dd><code><?= esc($install['path'] ?? '') ?></code></dd>
            <dt>Status</dt>
            <dd>
                <?php if (! empty($install['present'])) : ?>
                    <span class="ok">File exists</span>
                <?php else : ?>
                    <span class="bad">Not found</span>
                <?php endif; ?>
            </dd>
        </dl>
    </div>

    <div class="panel">
        <h2>Writable directories</h2>
        <table>
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Path</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($writable as $w) : ?>
                    <tr>
                        <td><?= esc($w['label'] ?? '') ?></td>
                        <td><code><?= esc($w['path'] ?? '') ?></code></td>
                        <td>
                            <?php if (! empty($w['ok'])) : ?>
                                <span class="ok">OK</span>
                            <?php else : ?>
                                <span class="bad"><?= esc($w['detail'] ?? '') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>Database</h2>
        <?php if (! empty($db['error'])) : ?>
            <p class="bad" style="margin-top:0"><?= esc($db['error']) ?></p>
        <?php endif; ?>
        <dl>
            <dt>Driver</dt>
            <dd><code><?= esc($db['driver'] ?? '') ?></code></dd>
            <dt>Connection group</dt>
            <dd><code><?= esc(isset($groupName) ? (string) $groupName : 'default') ?></code></dd>
            <dt>Hostname</dt>
            <dd><?= esc($db['hostname'] !== '' ? $db['hostname'] : '—') ?></dd>
            <dt>Database / file</dt>
            <dd><?= esc($db['database'] !== '' ? $db['database'] : '—') ?></dd>
            <?php if (($db['schema'] ?? '') !== '') : ?>
                <dt>Schema</dt>
                <dd><code><?= esc($db['schema']) ?></code></dd>
            <?php endif; ?>
            <dt>Table prefix</dt>
            <dd><code><?= esc($db['prefix'] ?? '') ?></code> <?= ($db['prefix'] ?? '') === '' ? '<span class="muted">(none)</span>' : '' ?></dd>
            <dt>Query test</dt>
            <dd><?= ! empty($db['connected']) ? '<span class="ok">SELECT 1 OK</span>' : '<span class="bad">Failed</span>' ?></dd>
        </dl>

        <p class="muted" style="margin:.75rem 0 .35rem">Preset tables (from installer schema import)</p>
        <table>
            <thead>
                <tr>
                    <th>Table</th>
                    <th>Physical name</th>
                    <th>Exists</th>
                    <th>Rows</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($db['tables'] ?? [] as $t) : ?>
                    <tr>
                        <td><code><?= esc($t['name'] ?? '') ?></code></td>
                        <td><code><?= esc($t['physical'] ?? '') ?></code></td>
                        <td>
                            <?php if (! empty($t['exists'])) : ?>
                                <span class="ok">Yes</span>
                            <?php else : ?>
                                <span class="bad">No</span>
                            <?php endif; ?>
                            <?php if (! empty($t['error'])) : ?>
                                <br><span class="warn"><?= esc($t['error']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= isset($t['rows']) ? esc((string) $t['rows']) : '—' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>PHP extensions</h2>
        <table>
            <thead>
                <tr>
                    <th>Extension</th>
                    <th>Loaded</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exts as $e) : ?>
                    <tr>
                        <td><code><?= esc($e['name'] ?? '') ?></code></td>
                        <td><?= ! empty($e['loaded']) ? '<span class="ok">Yes</span>' : '<span class="bad">No</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="muted"><a href="<?= esc(site_url('DashBoard')) ?>">← Back to dashboard</a></p>
</main>
</body>
</html>
