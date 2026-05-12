<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Edit site settings') ?></title>
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
        }
        label { display: block; font-weight: 600; font-size: .85rem; margin-top: 1rem; color: #334155; }
        .hint { font-weight: 400; color: #64748b; font-size: .8rem; }
        input[type="text"], textarea {
            width: 100%; max-width: 100%; box-sizing: border-box;
            margin-top: .35rem; padding: .5rem .65rem; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit;
        }
        textarea { min-height: 8rem; resize: vertical; }
        .err { color: #b91c1c; font-size: .85rem; margin-top: .25rem; }
        .actions { margin-top: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        button.primary {
            background: #2563eb; color: #fff; border: none; padding: .55rem 1rem; border-radius: 8px; cursor: pointer; font: inherit;
        }
        button.primary:hover { background: #1d4ed8; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'Edit') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Site settings</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">SEO</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
    </nav>
</header>
<main>
    <div class="panel">
        <?= form_open($action ?? '') ?>
            <?= csrf_field() ?>

            <?php if (($errors ?? []) !== []) : ?>
                <p style="color:#b91c1c;font-size:.9rem;">Please fix the highlighted fields.</p>
            <?php endif; ?>
            <?php if (! empty($errors['_form'])) : ?>
                <p style="color:#b91c1c;font-size:.9rem;margin:0 0 1rem;"><?= esc($errors['_form']) ?></p>
            <?php endif; ?>

            <label for="site_name">Site name <span class="hint">Shown in the header and home hero when set</span></label>
            <input type="text" name="site_name" id="site_name" maxlength="255" value="<?= esc(old('site_name', (string) ($record['site_name'] ?? ''))) ?>">
            <?php if (! empty($errors['site_name'])) : ?><div class="err"><?= esc($errors['site_name']) ?></div><?php endif; ?>

            <label for="site_description">Site description <span class="hint">Home hero line and meta description when set</span></label>
            <textarea name="site_description" id="site_description"><?= esc(old('site_description', (string) ($record['site_description'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['site_description'])) : ?><div class="err"><?= esc($errors['site_description']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="primary">Save</button>
                <a href="<?= esc(site_url('DashBoard/Web_Settings')) ?>">Cancel</a>
            </div>
        <?= form_close() ?>
    </div>
</main>
</body>
</html>
