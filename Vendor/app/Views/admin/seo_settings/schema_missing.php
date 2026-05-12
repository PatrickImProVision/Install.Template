<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SEO settings') ?></title>
    <style>
        body { margin: 0; font-family: system-ui, sans-serif; background: #f4f6f9; color: #1a1a1a; line-height: 1.55; }
        header {
            background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        }
        header h1 { font-size: 1.1rem; margin: 0; }
        main { max-width: 720px; margin: 0 auto; padding: 2rem 1.25rem; }
        .panel { background: #fff; border-radius: 10px; padding: 1.5rem; border: 1px solid #e2e8f0; }
        code { font-size: .85rem; background: #f1f5f9; padding: .15rem .35rem; border-radius: 4px; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'SEO settings') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
    </nav>
</header>
<main>
    <div class="panel">
        <p>The <code>seo_pages</code> table is not in the database yet.</p>
        <p>Import <code>008_seo_pages.sql</code> (or the matching driver file in <code>app/Database/Source/</code>).</p>
        <p>For PostgreSQL: <code>php spark db:import-preset</code>.</p>
        <p><a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">Reload</a></p>
    </div>
</main>
</body>
</html>
