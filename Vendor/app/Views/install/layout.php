<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Installation') ?></title>
    <style>
        :root {
            --bg: #0f1419;
            --panel: #1a2332;
            --text: #e7eef8;
            --muted: #8b9cb3;
            --accent: #3d8bfd;
            --danger: #f87171;
            --ok: #34d399;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: radial-gradient(1200px 600px at 20% -10%, #1e3a5f 0%, transparent 55%), var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        .wrap {
            max-width: min(94vw, 880px);
            margin: 0 auto;
            padding: 2rem 1.25rem 3rem;
        }
        .card {
            background: var(--panel);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        h1 {
            font-size: 1.35rem;
            font-weight: 600;
            margin: 0 0 0.35rem;
            letter-spacing: -0.02em;
        }
        .sub {
            color: var(--muted);
            font-size: 0.9rem;
            margin: 0 0 1.5rem;
        }
        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            margin: 0 0 0.35rem;
            color: var(--muted);
        }
        input[type="text"], input[type="password"], input[type="number"], input[type="email"], textarea,
        select {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(0, 0, 0, 0.25);
            color: var(--text);
            margin-bottom: 1rem;
        }
        select {
            cursor: pointer;
        }
        textarea { min-height: 72px; resize: vertical; }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(61, 139, 253, 0.25);
        }
        .row {
            display: flex;
            gap: 0.75rem;
        }
        .row > div { flex: 1; min-width: 0; }
        /* Wide forms: two columns on larger screens, one column on phones */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem 1.25rem;
            align-items: start;
        }
        .form-grid > .stack {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .form-grid > .stack input,
        .form-grid > .stack select,
        .form-grid > .stack textarea {
            margin-bottom: 0;
        }
        .form-grid .full {
            grid-column: 1 / -1;
        }
        .form-grid.form-host-port {
            grid-template-columns: minmax(0, 2fr) minmax(120px, 1fr);
        }
        @media (max-width: 640px) {
            .form-grid,
            .form-grid.form-host-port {
                grid-template-columns: 1fr;
            }
        }
        .pw-field {
            position: relative;
            width: 100%;
        }
        .pw-field input {
            padding-right: 3.25rem;
        }
        .pw-toggle {
            position: absolute;
            right: 0.3rem;
            top: 50%;
            transform: translateY(-50%);
            padding: 0.4rem 0.55rem;
            margin: 0;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.08);
            color: var(--muted);
            border: 1px solid rgba(255, 255, 255, 0.1);
            line-height: 1;
        }
        .pw-toggle:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.12);
        }
        .pw-toggle:focus-visible {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(61, 139, 253, 0.25);
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 0.5rem;
        }
        .form-grid > .actions.full {
            margin-top: 0.35rem;
        }
        button, .btn {
            cursor: pointer;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        button.primary, .btn.primary {
            background: var(--accent);
            color: #fff;
        }
        button.secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text);
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .alert.err { background: rgba(248, 113, 113, 0.15); color: var(--danger); border: 1px solid rgba(248, 113, 113, 0.35); }
        .alert.ok { background: rgba(52, 211, 153, 0.15); color: var(--ok); border: 1px solid rgba(52, 211, 153, 0.35); }
        ul.errors {
            margin: 0 0 1rem;
            padding-left: 1.25rem;
            color: var(--danger);
            font-size: 0.88rem;
        }
        .steps {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="steps">Installation wizard</div>
    <div class="card">
        <?php
        // Inline alerts (same request) take precedence over flash (after redirect).
        $flashOk = (isset($installAlertSuccess) && $installAlertSuccess !== '')
            ? $installAlertSuccess
            : session()->getFlashdata('success');
        $flashErr = (isset($installAlertError) && $installAlertError !== '')
            ? $installAlertError
            : session()->getFlashdata('error');
        $inlineErrs = (isset($installFieldErrors) && is_array($installFieldErrors)) ? $installFieldErrors : [];
        $flashErrors = $inlineErrs !== [] ? $inlineErrs : (session()->getFlashdata('errors') ?? []);
        ?>
        <?php
        // Single anchor for scroll-to-feedback (database step script).
        $feedbackAnchorId = 'install-db-result';
        $anchorUsed         = false;
        ?>
        <?php if ($flashOk): ?>
            <div class="alert ok" role="status"<?= $anchorUsed ? '' : ' id="' . $feedbackAnchorId . '"' ?>><?= esc($flashOk) ?></div>
            <?php $anchorUsed = true; ?>
        <?php endif; ?>
        <?php if ($flashErr): ?>
            <div class="alert err" role="alert"<?= $anchorUsed ? '' : ' id="' . $feedbackAnchorId . '"' ?>><?= esc($flashErr) ?></div>
            <?php $anchorUsed = true; ?>
        <?php endif; ?>
        <?php if (! empty($flashErrors) && is_array($flashErrors)): ?>
            <ul class="errors" role="alert"<?= $anchorUsed ? '' : ' id="' . $feedbackAnchorId . '"' ?>>
                <?php foreach ($flashErrors as $err): ?>
                    <li><?= esc(is_array($err) ? implode(' ', $err) : $err) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</div>
</body>
</html>
