<!DOCTYPE html>
<html lang="en">
<?php
$seo = $seo ?? ['title' => '', 'description' => '', 'keywords' => ''];
?>
<head>
    <meta charset="UTF-8">
    <title><?= esc((string) ($seo['title'] ?? '')) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc((string) ($seo['description'] ?? '')) ?>">
    <meta name="keywords" content="<?= esc((string) ($seo['keywords'] ?? '')) ?>">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <style {csp-style-nonce}>
<?= view('partials/site_theme_styles') ?>
    </style>
</head>
<body>

<div class="site-main contact-page-main">
<?= view('partials/site_header', ['installed' => $installed, 'siteName' => $siteName, 'navCurrent' => 'contact']) ?>

<?= view('partials/contact_page_hero', [
    'installed'              => $installed,
    'contactPageHeading'     => $contactPageHeading ?? null,
    'contactUnavailable'     => $contactUnavailable ?? false,
    'contactSchemaMissing'   => $contactSchemaMissing ?? false,
]) ?>

</div>

<?= view('partials/site_footer', ['contactRows' => $contactRows ?? []]) ?>

</body>
</html>
