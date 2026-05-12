<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Site settings') ?></title>
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
        main { max-width: 720px; margin: 0 auto; padding: 2rem 1.25rem; }
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
        .desc-preview { max-height: 6rem; overflow: hidden; color: #475569; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'Site settings') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">SEO</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/About_Us')) ?>">About us (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Services')) ?>">Services (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Tech_Stack')) ?>">Technology (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Products')) ?>">Products (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Values')) ?>">Values (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Site_Contacts')) ?>">Contact (CMS)</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('/')) ?>">Home</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('logout')) ?>">Sign out</a>
    </nav>
</header>
<main>
    <?php
    $flashMsg    = session()->getFlashdata('success');
    $savedBanner = ! empty($savedBanner) || ($flashMsg !== null && $flashMsg !== '');
    $bannerText  = ($flashMsg !== null && $flashMsg !== '') ? (string) $flashMsg : 'Site settings saved.';
    ?>
    <?php if ($savedBanner) : ?>
    <div role="status" style="background:#ecfdf5;border:1px solid #10b981;color:#064e3b;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;">
        <?= esc($bannerText) ?>
    </div>
    <?php endif; ?>

    <div class="panel">
        <p class="muted">Public site name and description (home hero, meta description when set).</p>
        <table>
            <thead>
                <tr>
                    <th>Site name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= esc((string) ($settings['site_name'] ?? '')) ?></td>
                    <td class="desc-preview"><?= nl2br(esc((string) ($settings['site_description'] ?? '')), false) ?></td>
                    <td><a href="<?= esc(site_url('DashBoard/Web_Settings/Edit/1')) ?>">Edit</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
