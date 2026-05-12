<?php

declare(strict_types=1);

/** @var bool $installed */
/** @var string|null $siteName */

helper('url');

$homeBrand  = ! empty($siteName) ? $siteName : 'Site';
$navCurrent = $navCurrent ?? '';

?>
<header class="site-topbar">
    <div class="wrap site-topbar-inner">
        <a class="site-topbar-brand" href="<?= site_url('/') ?>"><?= esc($homeBrand) ?></a>
        <nav class="site-topbar-nav" aria-label="Site">
            <a href="<?= site_url('/') ?>">Home</a>
            <a href="<?= site_url('about-us') ?>"<?= $navCurrent === 'about-us' ? ' aria-current="page"' : '' ?>>About us</a>
            <a href="<?= site_url('services') ?>"<?= ($navCurrent ?? '') === 'services' ? ' aria-current="page"' : '' ?>>Services</a>
            <a href="<?= site_url('tech-stack') ?>"<?= $navCurrent === 'tech-stack' ? ' aria-current="page"' : '' ?>>Technology</a>
            <a href="<?= site_url('products') ?>"<?= $navCurrent === 'products' ? ' aria-current="page"' : '' ?>>Products</a>
            <a href="<?= site_url('values') ?>"<?= $navCurrent === 'values' ? ' aria-current="page"' : '' ?>>Values</a>
            <a href="<?= site_url('contact') ?>"<?= $navCurrent === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a>
            <span class="site-topbar-account" aria-label="Account">
            <?php if (! empty($installed)) : ?>
                <?php if (session()->get('user_id')) : ?>
                    <a href="<?= site_url('logout') ?>">Sign out</a>
                    <?php if (session()->get('user_role') === 'administrator') : ?>
                        <a href="<?= site_url('DashBoard') ?>">Dashboard</a>
                    <?php endif; ?>
                <?php else : ?>
                    <a href="<?= site_url('login') ?>">Sign in</a>
                <?php endif; ?>
            <?php else : ?>
                <a href="<?= site_url('install/database') ?>">Setup</a>
            <?php endif; ?>
            </span>
        </nav>
    </div>
</header>
