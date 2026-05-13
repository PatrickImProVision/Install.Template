<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Product Store') ?></title>
    <style>
        :root { --bg: #0f1419; --card: #1a2332; --text: #e7ecf3; --muted: #8b9cb3; --accent: #3d8bfd; --danger: #e05252; --ok: #3ecf8e; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, Segoe UI, Roboto, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; }
        .wrap { max-width: 640px; margin: 0 auto; padding: 2rem 1.25rem; }
        h1 { font-size: 1.35rem; font-weight: 600; margin: 0 0 0.5rem; }
        p.lead { color: var(--muted); margin: 0 0 1.5rem; font-size: 0.95rem; }
        .card { background: var(--card); border-radius: 10px; padding: 1.5rem; border: 1px solid rgba(255,255,255,.06); margin-bottom: 1rem; }
        .card:last-of-type { margin-bottom: 0; }
        label { display: block; font-size: 0.8rem; color: var(--muted); margin: 0.75rem 0 0.25rem; }
        input:not([type="checkbox"]):not([type="radio"]), select, textarea { width: 100%; padding: 0.55rem 0.65rem; border-radius: 6px; border: 1px solid rgba(255,255,255,.12); background: #0c1016; color: var(--text); font-size: 0.95rem; }
        input[type="checkbox"], input[type="radio"] { width: 1.125rem; height: 1.125rem; margin: 0; padding: 0; flex-shrink: 0; accent-color: var(--accent); cursor: pointer; }
        label.field-check { display: flex; align-items: flex-start; gap: 0.65rem; margin: 0.6rem 0 0; font-size: 0.9rem; color: var(--text); line-height: 1.45; cursor: pointer; }
        label.field-check:first-of-type { margin-top: 0; }
        label.field-check .field-check-text { flex: 1; min-width: 0; color: var(--text); }
        label.field-check .field-check-text code { vertical-align: baseline; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .row-host-port { grid-template-columns: 1fr minmax(5rem, 6.75rem); align-items: end; }
        .row-db-prefix { grid-template-columns: 1fr minmax(6.5rem, 9rem); align-items: start; }
        .row-user-pass { grid-template-columns: 1fr 1fr; align-items: end; }
        .field-password { position: relative; width: 100%; }
        .field-password > input { padding-right: 4.5rem; }
        @media (max-width: 520px) { .row { grid-template-columns: 1fr; } }
        .actions { margin-top: 1.25rem; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }
        button, .btn { cursor: pointer; border: 0; border-radius: 6px; padding: 0.6rem 1rem; font-size: 0.9rem; font-weight: 500; text-decoration: none; display: inline-block; }
        button.password-toggle {
            position: absolute;
            right: 0.35rem;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,.1);
            color: var(--text);
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 4px;
            line-height: 1.2;
            cursor: pointer;
        }
        button.password-toggle:hover { background: rgba(255,255,255,.16); }
        button.password-toggle:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
        button.password-toggle:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-secondary { background: rgba(255,255,255,.08); color: var(--text); }
        .btn-danger { background: var(--danger); color: #fff; }
        a.btn-primary, .prose a.btn-primary { color: #fff; }
        a.btn-secondary, .prose a.btn-secondary { color: var(--text); }
        a.btn-danger, .prose a.btn-danger { color: #fff; }
        .err { background: rgba(224,82,82,.12); border: 1px solid rgba(224,82,82,.35); color: #ffb4b4; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .ok { background: rgba(62,207,142,.12); border: 1px solid rgba(62,207,142,.35); color: #b8f5d3; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; }
        .hint { font-size: 0.8rem; color: var(--muted); margin-top: 0.35rem; }
        code { font-size: 0.85em; background: rgba(0,0,0,.35); padding: 0.1em 0.35em; border-radius: 4px; }
        .prose h2 { font-size: 1rem; font-weight: 600; margin: 0 0 0.5rem; color: var(--text); }
        .prose p { color: var(--muted); margin: 0.5rem 0; }
        .prose pre { background: #0c1016; border: 1px solid rgba(255,255,255,.08); border-radius: 8px; padding: 0.85rem 1rem; overflow-x: auto; font-size: 0.82rem; color: var(--text); margin: 0.75rem 0; }
        .prose a { color: var(--accent); text-decoration: none; }
        .prose a:hover { text-decoration: underline; }
        .meta { font-size: 0.75rem; color: var(--muted); text-align: center; margin-top: 1.5rem; }
    </style>
</head>
<body>
<div class="wrap">
    <?= $this->renderSection('main') ?>
</div>
</body>
</html>
