<!DOCTYPE html>
<html lang="en">
<?php
$heroTitle = trim((string) ($siteName ?? ''));
$heroLead  = (! empty($installed) && ! empty($siteDescription))
    ? trim((string) $siteDescription)
    : '';
$seo       = $seo ?? ['title' => '', 'description' => '', 'keywords' => ''];
$pageTitle = trim((string) ($seo['title'] ?? ''));
if ($pageTitle === '') {
    $pageTitle = $heroTitle !== '' ? $heroTitle : 'Home';
}
?>
<head>
    <meta charset="UTF-8">
    <title><?= esc($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc((string) ($seo['description'] ?? '')) ?>">
    <meta name="keywords" content="<?= esc((string) ($seo['keywords'] ?? '')) ?>">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <style {csp-style-nonce}>
<?= view('partials/site_theme_styles') ?>
    </style>
</head>
<body>

<div class="site-main">
<?= view('partials/site_header', ['installed' => $installed, 'siteName' => $siteName]) ?>

<section class="hero" id="home" aria-labelledby="hero-title">
    <div class="hero-inner">
        <h1 id="hero-title"><?= esc($heroTitle !== '' ? $heroTitle : 'Welcome') ?></h1>
        <?php if ($heroLead !== '') : ?>
            <p class="tagline"><?= esc($heroLead) ?></p>
        <?php endif; ?>
    </div>
</section>

</div>

<?= view('partials/site_footer') ?>

<?php if (! empty($scrollTo)) : ?>
<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById(<?= json_encode($scrollTo, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>
<?php endif; ?>

</body>
</html>
