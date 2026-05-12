<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Edit SEO') ?></title>
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
        textarea { min-height: 5rem; resize: vertical; }
        .err { color: #b91c1c; font-size: .85rem; margin-top: .25rem; }
        .actions { margin-top: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        button.primary {
            background: #2563eb; color: #fff; border: none; padding: .55rem 1rem; border-radius: 8px; cursor: pointer; font: inherit;
        }
        button.primary:hover { background: #1d4ed8; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        code { font-size: .85rem; background: #f1f5f9; padding: .1rem .35rem; border-radius: 4px; }
    </style>
</head>
<body>
<header>
    <h1><?= esc($title ?? 'SEO') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">All pages</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
    </nav>
</header>
<main>
    <div class="panel">
        <p style="margin:0 0 1rem;color:#64748b;font-size:.9rem;">Page: <strong><?= esc($pageLabel ?? '') ?></strong> · <code><?= esc($pageKey ?? '') ?></code></p>

        <?= form_open($action ?? '') ?>
            <?= csrf_field() ?>

            <?php if (! empty($errors['_form'])) : ?>
                <p style="color:#b91c1c;font-size:.9rem;margin:0 0 1rem;"><?= esc($errors['_form']) ?></p>
            <?php endif; ?>

            <?php if ($errors !== []) : ?>
                <p style="color:#b91c1c;font-size:.9rem;">Please fix the highlighted fields.</p>
            <?php endif; ?>

            <label for="meta_title">Meta title <span class="hint">HTML &lt;title&gt;; leave empty to use the site default for this page.</span></label>
            <input type="text" name="meta_title" id="meta_title" maxlength="255" value="<?= esc(old('meta_title', (string) ($record['meta_title'] ?? ''))) ?>">
            <?php if (! empty($errors['meta_title'])) : ?><div class="err"><?= esc($errors['meta_title']) ?></div><?php endif; ?>

            <label for="meta_description">Meta description</label>
            <textarea name="meta_description" id="meta_description"><?= esc(old('meta_description', (string) ($record['meta_description'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['meta_description'])) : ?><div class="err"><?= esc($errors['meta_description']) ?></div><?php endif; ?>

            <label for="meta_keywords">Meta keywords <span class="hint">Comma-separated; leave empty for defaults.</span></label>
            <textarea name="meta_keywords" id="meta_keywords" style="min-height:3.5rem;"><?= esc(old('meta_keywords', (string) ($record['meta_keywords'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['meta_keywords'])) : ?><div class="err"><?= esc($errors['meta_keywords']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="primary">Save</button>
                <a href="<?= esc(site_url('DashBoard/SEO_Settings')) ?>">Cancel</a>
            </div>
        <?= form_close() ?>
    </div>
</main>
</body>
</html>
