<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Values block') ?></title>
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
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; max-width: 100%; box-sizing: border-box;
            margin-top: .35rem; padding: .5rem .65rem; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit;
        }
        textarea { min-height: 7rem; resize: vertical; }
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
    <h1><?= esc($title ?? 'Block') ?></h1>
    <nav>
        <a href="<?= esc(site_url('DashBoard/Values')) ?>">All blocks</a>
        &nbsp;·&nbsp;
        <a href="<?= esc(site_url('DashBoard')) ?>">Dashboard</a>
    </nav>
</header>
<main>
    <div class="panel">
        <?= form_open($action ?? '') ?>
            <?= csrf_field() ?>

            <?php
            $flashErr = session()->getFlashdata('error');
            if ($flashErr !== null && $flashErr !== '') :
                ?>
                <p style="color:#b91c1c;font-size:.9rem;margin:0 0 1rem;"><?= esc((string) $flashErr) ?></p>
            <?php endif; ?>

            <?php if (! empty($errors['_form'])) : ?>
                <p style="color:#b91c1c;font-size:.9rem;margin:0 0 1rem;"><?= esc($errors['_form']) ?></p>
            <?php endif; ?>

            <?php if ($errors !== []) : ?>
                <p style="color:#b91c1c;font-size:.9rem;">Please fix the highlighted fields.</p>
            <?php endif; ?>

            <label for="sort_order">Sort order</label>
            <input type="number" name="sort_order" id="sort_order" value="<?= esc(old('sort_order', (string) ($record['sort_order'] ?? 0))) ?>">
            <?php if (! empty($errors['sort_order'])) : ?><div class="err"><?= esc($errors['sort_order']) ?></div><?php endif; ?>

            <label for="kind">Kind <span class="hint">page_heading = section title (optional subtitle); value_item = one column with emoji + text</span></label>
            <select name="kind" id="kind">
                <?php foreach (\App\Controllers\Admin\ValuesItems::KINDS as $k) : ?>
                    <option value="<?= esc($k) ?>"<?= old('kind', $record['kind'] ?? '') === $k ? ' selected' : '' ?>><?= esc($k) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (! empty($errors['kind'])) : ?><div class="err"><?= esc($errors['kind']) ?></div><?php endif; ?>

            <label for="title">Title</label>
            <input type="text" name="title" id="title" maxlength="255" value="<?= esc(old('title', (string) ($record['title'] ?? ''))) ?>">
            <?php if (! empty($errors['title'])) : ?><div class="err"><?= esc($errors['title']) ?></div><?php endif; ?>

            <label for="description">Description <span class="hint">Optional intro under the heading, or body text for a value item</span></label>
            <textarea name="description" id="description"><?= esc(old('description', (string) ($record['description'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['description'])) : ?><div class="err"><?= esc($errors['description']) ?></div><?php endif; ?>

            <label for="emoji">Emoji / icon <span class="hint">Value items — paste one emoji or short symbol (shown above the title)</span></label>
            <input type="text" name="emoji" id="emoji" maxlength="64" value="<?= esc(old('emoji', (string) ($record['emoji'] ?? ''))) ?>" placeholder="🎯">
            <?php if (! empty($errors['emoji'])) : ?><div class="err"><?= esc($errors['emoji']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="primary">Save</button>
                <a href="<?= esc(site_url('DashBoard/Values')) ?>">Cancel</a>
            </div>
        <?= form_close() ?>
    </div>
</main>
</body>
</html>
