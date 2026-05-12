<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Technology stack block') ?></title>
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
        textarea.svg-area { min-height: 10rem; font-family: ui-monospace, monospace; font-size: .8rem; }
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
        <a href="<?= esc(site_url('DashBoard/Tech_Stack')) ?>">All blocks</a>
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

            <label for="kind">Kind <span class="hint">page_heading = section title &amp; subtitle; tech_card = one technology tile</span></label>
            <select name="kind" id="kind">
                <?php foreach (\App\Controllers\Admin\TechStackItems::KINDS as $k) : ?>
                    <option value="<?= esc($k) ?>"<?= old('kind', $record['kind'] ?? '') === $k ? ' selected' : '' ?>><?= esc($k) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (! empty($errors['kind'])) : ?><div class="err"><?= esc($errors['kind']) ?></div><?php endif; ?>

            <label for="title">Title <span class="hint">Page heading — section title</span></label>
            <input type="text" name="title" id="title" maxlength="255" value="<?= esc(old('title', (string) ($record['title'] ?? ''))) ?>">
            <?php if (! empty($errors['title'])) : ?><div class="err"><?= esc($errors['title']) ?></div><?php endif; ?>

            <label for="description">Description <span class="hint">Page heading — subtitle; optional for cards</span></label>
            <textarea name="description" id="description"><?= esc(old('description', (string) ($record['description'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['description'])) : ?><div class="err"><?= esc($errors['description']) ?></div><?php endif; ?>

            <label for="category">Category <span class="hint">Tech card — label above product name (e.g. Infrastructure)</span></label>
            <input type="text" name="category" id="category" maxlength="255" value="<?= esc(old('category', (string) ($record['category'] ?? ''))) ?>">
            <?php if (! empty($errors['category'])) : ?><div class="err"><?= esc($errors['category']) ?></div><?php endif; ?>

            <label for="product_name">Product name</label>
            <input type="text" name="product_name" id="product_name" maxlength="255" value="<?= esc(old('product_name', (string) ($record['product_name'] ?? ''))) ?>">
            <?php if (! empty($errors['product_name'])) : ?><div class="err"><?= esc($errors['product_name']) ?></div><?php endif; ?>

            <label for="blurb">Blurb</label>
            <textarea name="blurb" id="blurb"><?= esc(old('blurb', (string) ($record['blurb'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['blurb'])) : ?><div class="err"><?= esc($errors['blurb']) ?></div><?php endif; ?>

            <label for="href">Link URL</label>
            <input type="text" name="href" id="href" maxlength="1024" value="<?= esc(old('href', (string) ($record['href'] ?? ''))) ?>" placeholder="https://">
            <?php if (! empty($errors['href'])) : ?><div class="err"><?= esc($errors['href']) ?></div><?php endif; ?>

            <label for="icon_color">Icon color <span class="hint">CSS color for icon (e.g. #2563eb)</span></label>
            <input type="text" name="icon_color" id="icon_color" maxlength="32" value="<?= esc(old('icon_color', (string) ($record['icon_color'] ?? ''))) ?>">
            <?php if (! empty($errors['icon_color'])) : ?><div class="err"><?= esc($errors['icon_color']) ?></div><?php endif; ?>

            <label for="name_color">Name color <span class="hint">CSS color for product name</span></label>
            <input type="text" name="name_color" id="name_color" maxlength="32" value="<?= esc(old('name_color', (string) ($record['name_color'] ?? ''))) ?>">
            <?php if (! empty($errors['name_color'])) : ?><div class="err"><?= esc($errors['name_color']) ?></div><?php endif; ?>

            <label for="icon_svg">Icon SVG <span class="hint">Tech cards — paste full &lt;svg&gt;…&lt;/svg&gt;</span></label>
            <textarea name="icon_svg" id="icon_svg" class="svg-area"><?= esc(old('icon_svg', (string) ($record['icon_svg'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['icon_svg'])) : ?><div class="err"><?= esc($errors['icon_svg']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="primary">Save</button>
                <a href="<?= esc(site_url('DashBoard/Tech_Stack')) ?>">Cancel</a>
            </div>
        <?= form_close() ?>
    </div>
</main>
</body>
</html>
