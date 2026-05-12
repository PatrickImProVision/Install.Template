<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'About us CMS') ?></title>
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
        .actions { white-space: nowrap; }
        .actions form { display: inline; margin-left: .35rem; }
        button.link {
            background: none; border: none; color: #b91c1c; cursor: pointer;
            font: inherit; padding: 0; text-decoration: underline;
        }
        button.link:hover { color: #991b1b; }
        .top-actions { margin-bottom: 1rem; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'About us blocks') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">SEO</a>
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
    <?php if ($m = session()->getFlashdata('success')) : ?>
        <p class="muted" style="color:#059669"><?= esc($m) ?></p>
    <?php endif; ?>

    <div class="top-actions">
        <a href="<?= esc(site_url('DashBoard/About_Us/Create')) ?>">+ New block</a>
    </div>

    <div class="panel">
        <p class="muted">Each row is one block on the public About us page. Sort order controls display order within each column group.</p>

        <?php if ($items === []) : ?>
            <p>No blocks yet. <a href="<?= esc(site_url('DashBoard/About_Us/Create')) ?>">Create the first block</a>.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Placement</th>
                        <th>Kind</th>
                        <th>Title</th>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $row) : ?>
                        <tr>
                            <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
                            <td><?= esc((string) ($row['placement'] ?? '')) ?></td>
                            <td><?= esc((string) ($row['kind'] ?? '')) ?></td>
                            <td><?php
                                $t = (string) ($row['title'] ?? '');
                                echo esc(strlen($t) > 72 ? substr($t, 0, 69) . '…' : $t);
                            ?></td>
                            <td class="actions">
                                <a href="<?= esc(site_url('DashBoard/About_Us/Edit/' . (int) ($row['id'] ?? 0))) ?>">Edit</a>
                                <?= form_open(site_url('DashBoard/About_Us/Delete/' . (int) ($row['id'] ?? 0))) ?>
                                    <?= csrf_field() ?>
                                    <button type="submit" class="link" onclick="return confirm('Delete this block?');">Delete</button>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
