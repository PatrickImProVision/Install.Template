<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>
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
        .panel h2 { margin: 0 0 .75rem; font-size: 1rem; }
        .muted { color: #64748b; font-size: .9rem; margin: 0; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<header>
    <h1>Dashboard</h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard/Index')) ?>"><strong>Status</strong></a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a>
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
    <?php if ($m = session()->getFlashdata('success')): ?>
        <p class="muted" style="color:#059669"><?= esc($m) ?></p>
    <?php endif; ?>
    <?php if (! empty($siteSettingsSchemaMissing)) : ?>
        <div class="panel" style="border-color:#f97316;background:#fff7ed;">
            <h2 style="margin-top:0;">Database setup needed</h2>
            <p class="muted" style="color:#9a3412;">The <code style="background:#ffedd5;padding:0.1rem 0.35rem;border-radius:4px;">site_settings</code> table is missing. Import <code style="background:#ffedd5;padding:0.1rem 0.35rem;border-radius:4px;">001_preset_tables*.sql</code> from <code style="background:#ffedd5;padding:0.1rem 0.35rem;border-radius:4px;">app/Database/Source/</code> for your driver, or use <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a> after import.</p>
            <p class="muted"><a href="<?= esc(site_url('DashBoard/Index')) ?>">Open system status</a> to verify tables.</p>
        </div>
    <?php endif; ?>
    <div class="panel">
        <h2>System status</h2>
        <p><a href="<?= esc(site_url('DashBoard/Index')) ?>">Open system status</a> — PHP, database, install flag, writable paths, preset tables, and extensions.</p>
    </div>
    <div class="panel">
        <h2>Site settings</h2>
        <p><strong>Name:</strong> <?= esc($settings['site_name'] ?? '') ?></p>
        <p class="muted"><strong>Description:</strong><br><?= nl2br(esc($settings['site_description'] ?? '')) ?></p>
        <p><a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Manage site settings</a> — edit site name and description.</p>
        <p><a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">SEO settings</a> — edit page titles, meta descriptions, and keywords per route.</p>
        <p><a href="<?= esc(site_url('DashBoard/About_Us')) ?>">Edit About us page blocks</a> — create, edit, or delete titles, descriptions, links, and card details.</p>
        <p><a href="<?= esc(site_url('DashBoard/Services')) ?>">Edit Services page blocks</a> — section heading and service cards (images, bullets, SVG icons).</p>
        <p><a href="<?= esc(site_url('DashBoard/Tech_Stack')) ?>">Edit Technology stack page blocks</a> — section heading and tech tiles (links, colors, SVG icons).</p>
        <p><a href="<?= esc(site_url('DashBoard/Products')) ?>">Edit Products &amp; services page blocks</a> — section heading and product cards (gradients, links, SVG icons).</p>
        <p><a href="<?= esc(site_url('DashBoard/Values')) ?>">Edit Our values page blocks</a> — section heading and value columns (emoji, titles, text).</p>
        <p><a href="<?= esc(site_url('DashBoard/Site_Contacts')) ?>">Edit Contact page &amp; site footer</a> — footer columns, links, legal lines, and contact hero.</p>
        <p class="muted">Extend this dashboard with forms and content tools as your application grows.</p>
    </div>
</main>
</body>
</html>
