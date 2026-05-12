<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Products block') ?></title>
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
        <a href="<?= esc(site_url('DashBoard/Products')) ?>">All blocks</a>
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

            <label for="kind">Kind <span class="hint">page_heading = section title &amp; subtitle; product_card = one gradient tile</span></label>
            <select name="kind" id="kind">
                <?php foreach (\App\Controllers\Admin\ProductsItems::KINDS as $k) : ?>
                    <option value="<?= esc($k) ?>"<?= old('kind', $record['kind'] ?? '') === $k ? ' selected' : '' ?>><?= esc($k) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (! empty($errors['kind'])) : ?><div class="err"><?= esc($errors['kind']) ?></div><?php endif; ?>

            <label for="title">Title</label>
            <input type="text" name="title" id="title" maxlength="255" value="<?= esc(old('title', (string) ($record['title'] ?? ''))) ?>">
            <?php if (! empty($errors['title'])) : ?><div class="err"><?= esc($errors['title']) ?></div><?php endif; ?>

            <label for="description">Description <span class="hint">Heading subtitle or card intro paragraph</span></label>
            <textarea name="description" id="description"><?= esc(old('description', (string) ($record['description'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['description'])) : ?><div class="err"><?= esc($errors['description']) ?></div><?php endif; ?>

            <label for="bullets">Bullets <span class="hint">Product cards only — one line per bullet</span></label>
            <textarea name="bullets" id="bullets"><?= esc(old('bullets', (string) ($record['bullets'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['bullets'])) : ?><div class="err"><?= esc($errors['bullets']) ?></div><?php endif; ?>

            <label for="sub_line">Footer line <span class="hint">Small text under the card (e.g. domain)</span></label>
            <input type="text" name="sub_line" id="sub_line" maxlength="255" value="<?= esc(old('sub_line', (string) ($record['sub_line'] ?? ''))) ?>">
            <?php if (! empty($errors['sub_line'])) : ?><div class="err"><?= esc($errors['sub_line']) ?></div><?php endif; ?>

            <label for="href">Link URL</label>
            <input type="text" name="href" id="href" maxlength="1024" value="<?= esc(old('href', (string) ($record['href'] ?? ''))) ?>" placeholder="https://">
            <?php if (! empty($errors['href'])) : ?><div class="err"><?= esc($errors['href']) ?></div><?php endif; ?>

            <label for="card_style">Card gradient <span class="hint">Product cards only</span></label>
            <select name="card_style" id="card_style">
                <?php foreach (\App\Controllers\Admin\ProductsItems::CARD_STYLES as $s) : ?>
                    <option value="<?= esc($s) ?>"<?= old('card_style', (string) ($record['card_style'] ?? 'grad-blue')) === $s ? ' selected' : '' ?>><?= esc($s) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (! empty($errors['card_style'])) : ?><div class="err"><?= esc($errors['card_style']) ?></div><?php endif; ?>

            <label for="icon_svg">Icon SVG <span class="hint">Product cards — paste full &lt;svg&gt;…&lt;/svg&gt;</span></label>
            <textarea name="icon_svg" id="icon_svg" class="svg-area"><?= esc(old('icon_svg', (string) ($record['icon_svg'] ?? ''))) ?></textarea>
            <?php if (! empty($errors['icon_svg'])) : ?><div class="err"><?= esc($errors['icon_svg']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="primary">Save</button>
                <a href="<?= esc(site_url('DashBoard/Products')) ?>">Cancel</a>
            </div>
        <?= form_close() ?>
    </div>
</main>
</body>
</html>
