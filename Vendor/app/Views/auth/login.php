<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Sign in') ?></title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, sans-serif;
            background: #0f1419;
            color: #e7eef8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .card {
            width: 100%;
            max-width: 400px;
            background: #1a2332;
            padding: 1.75rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.06);
        }
        h1 { font-size: 1.25rem; margin: 0 0 1rem; }
        label { display: block; font-size: .82rem; color: #8b9cb3; margin: 0 0 .35rem; }
        .field-input {
            width: 100%;
            box-sizing: border-box;
            padding: .65rem .75rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(0,0,0,.25);
            color: inherit;
            margin-bottom: 1rem;
        }
        .pw-field {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
        }
        .pw-field .field-input {
            margin-bottom: 0;
            padding-right: 3.35rem;
        }
        .pw-toggle {
            position: absolute;
            right: 0.35rem;
            top: 50%;
            transform: translateY(-50%);
            padding: 0.4rem 0.55rem;
            margin: 0;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.08);
            color: #8b9cb3;
            border: 1px solid rgba(255, 255, 255, 0.12);
            line-height: 1;
            cursor: pointer;
        }
        .pw-toggle:hover {
            color: #e7eef8;
            background: rgba(255, 255, 255, 0.14);
        }
        .pw-toggle:focus-visible {
            outline: none;
            border-color: #3d8bfd;
            box-shadow: 0 0 0 3px rgba(61, 139, 253, 0.25);
        }
        button[type="submit"] {
            width: 100%;
            padding: .7rem;
            border: none;
            border-radius: 8px;
            background: #3d8bfd;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }
        .alert { padding: .65rem .85rem; border-radius: 8px; margin-bottom: 1rem; font-size: .9rem; }
        .alert.err { background: rgba(248,113,113,.15); color: #f87171; }
        .alert.ok { background: rgba(52,211,153,.15); color: #34d399; }
        ul.errors { margin: 0 0 1rem; padding-left: 1.2rem; color: #f87171; font-size: .85rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>Sign in</h1>
    <?php if ($m = session()->getFlashdata('success')): ?>
        <div class="alert ok"><?= esc($m) ?></div>
    <?php endif; ?>
    <?php if ($m = session()->getFlashdata('error')): ?>
        <div class="alert err"><?= esc($m) ?></div>
    <?php endif; ?>
    <?php $errs = session()->getFlashdata('errors'); ?>
    <?php if (! empty($errs) && is_array($errs)): ?>
        <ul class="errors">
            <?php foreach ($errs as $e): ?>
                <li><?= esc(is_array($e) ? implode(' ', $e) : $e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?= form_open(site_url('login')) ?>
    <?= csrf_field() ?>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" class="field-input" value="<?= esc(old('email')) ?>" required autocomplete="username">
    <label for="password">Password</label>
    <div class="pw-field">
        <input type="password" name="password" id="password" class="field-input" required autocomplete="current-password">
        <button type="button" class="pw-toggle" data-pw-target="password" aria-label="Show password" aria-pressed="false">Show</button>
    </div>
    <button type="submit">Sign in</button>
    <?= form_close() ?>
</div>
<script>
(function () {
    document.querySelectorAll('.pw-toggle[data-pw-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-pw-target');
            var input = id ? document.getElementById(id) : null;
            if (!input) {
                return;
            }
            var showing = input.type === 'password';
            input.type = showing ? 'text' : 'password';
            btn.textContent = showing ? 'Hide' : 'Show';
            btn.setAttribute('aria-pressed', showing ? 'true' : 'false');
            btn.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');
        });
    });
})();
</script>
</body>
</html>
