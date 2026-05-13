<?= $this->extend('layouts/site') ?>
<?= $this->section('main') ?>
<?php if ($msg = session()->getFlashdata('message')): ?>
    <div class="ok"><?= esc($msg) ?></div>
<?php endif ?>

<h1>Product Store</h1>
<p class="lead">CodeIgniter 4 application with an installation wizard, preset SQL, and guarded routes until install completes.</p>

<div class="card prose">
    <h2>Getting started</h2>
    <p>This page is rendered from <code>app/Views/welcome_message.php</code> using the same layout and theme as the installer (<code>app/Views/layouts/site.php</code>).</p>
    <p>Application code lives under <code>Vendor/app/</code>; the public entry point is <code>Vendor/public/</code>.</p>
</div>

<div class="card prose">
    <h2>Installation</h2>
    <p><a href="<?= site_url('install') ?>">Open the installation wizard</a> to configure the database, run presets, and create an admin user.</p>
    <?php if (\App\Libraries\InstallationState::isInstalled()): ?>
        <p><a href="<?= site_url('install/uninstall') ?>">Uninstall</a> (backup, drop database, or remove flag only).</p>
    <?php endif ?>
</div>

<p class="meta">Environment: <?= esc(ENVIRONMENT) ?> · Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</p>
<?= $this->endSection() ?>
