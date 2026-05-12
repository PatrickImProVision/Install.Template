<?= $this->extend('install/layout') ?>

<?= $this->section('content') ?>
<h1>Database tables</h1>
<p class="sub">
    Driver <strong><?= esc($driver ?: '?') ?></strong>: files in <code>app/Database/Source</code> matching
    <strong><?= esc($presetSqlHint) ?></strong> are executed in alphabetical order.
</p>
<p class="sub">
    If these tables were already created (for example from the PostgreSQL CLI import), submitting here will detect them,
    skip duplicate work, and let you continue — that is normal.
</p>

<?= form_open('') ?>
<div class="actions">
    <button type="submit" class="primary">Import tables</button>
</div>
<?= form_close() ?>
<?= $this->endSection() ?>
