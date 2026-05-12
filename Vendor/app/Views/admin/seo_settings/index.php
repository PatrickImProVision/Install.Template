<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SEO settings') ?></title>
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
        main { max-width: 960px; margin: 0 auto; padding: 2rem 1.25rem; }
        .panel {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
        }
        .muted { color: #64748b; font-size: .9rem; margin: 0 0 1rem; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        th, td { text-align: left; padding: .5rem .65rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { font-weight: 600; color: #475569; font-size: .8rem; text-transform: uppercase; letter-spacing: .02em; }
        code { font-size: .85rem; background: #f1f5f9; padding: .1rem .35rem; border-radius: 4px; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'SEO') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('/')) ?>">Home</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('logout')) ?>">Sign out</a>
    </nav>
</header>
<main>
    <?php if ($m = session()->getFlashdata('success')) : ?>
        <p class="muted" style="color:#059669"><?= esc($m) ?></p>
    <?php endif; ?>

    <div class="panel">
        <p class="muted">Edit <strong>&lt;title&gt;</strong>, <strong>meta description</strong>, and <strong>keywords</strong> per public page. Leave fields empty to use the built-in default for that page (same behavior as before SEO settings).</p>

        <table>
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Route key</th>
                    <th>Custom title?</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($labels as $key => $label) : ?>
                    <?php
                    $row = $rowsByKey[$key] ?? null;
                    $hasCustom = $row !== null && (
                        trim((string) ($row['meta_title'] ?? '')) !== ''
                        || trim((string) ($row['meta_description'] ?? '')) !== ''
                        || trim((string) ($row['meta_keywords'] ?? '')) !== ''
                    );
                    ?>
                    <tr>
                        <td><?= esc($label) ?></td>
                        <td><code><?= esc($key) ?></code></td>
                        <td><?= $hasCustom ? 'Yes' : 'No (defaults)' ?></td>
                        <td><a href="<?= esc(site_url('DashBoard/SEO_Settings/Edit/' . $key)) ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
