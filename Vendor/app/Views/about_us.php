<!DOCTYPE html>
<html lang="en">
<?php
$seo = $seo ?? ['title' => '', 'description' => '', 'keywords' => ''];

$pageHeading = $aboutLayout['pageHeading'] ?? null;
$mission     = $aboutLayout['mission'] ?? null;
$badges      = $aboutLayout['badges'] ?? [];
$stackCards  = $aboutLayout['stackCards'] ?? [];
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

<div class="site-main about-page-main">
<?= view('partials/site_header', ['installed' => $installed, 'siteName' => $siteName, 'navCurrent' => 'about-us']) ?>

<?php if (! empty($aboutSchemaMissing) || ! empty($aboutUnavailable)) : ?>
<section class="block white about-body-first" id="about">
    <div class="wrap">
        <?= view('partials/public_section_fallback', [
            'sectionTitle'        => 'About us',
            'schemaMissing'       => ! empty($aboutSchemaMissing),
            'contentUnavailable'  => ! empty($aboutUnavailable),
            'installed'           => $installed ?? false,
            'manageUrl'           => site_url('DashBoard/About_Us'),
            'manageLabel'         => 'Manage About us blocks',
            'headingTag'          => 'h2',
        ]) ?>
    </div>
</section>
<?php else : ?>

<section class="block white about-body-first" id="about">
    <div class="wrap">
        <?php if ($pageHeading !== null) : ?>
        <div class="sec-head">
            <h2><?= esc((string) ($pageHeading['title'] ?? '')) ?></h2>
            <?php if (! empty($pageHeading['description'])) : ?>
                <p><?= nl2br(esc((string) $pageHeading['description']), false) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="about-grid">
            <div class="about-intro">
                <?php if ($mission !== null) : ?>
                    <h3><?= esc((string) ($mission['title'] ?? '')) ?></h3>
                    <?php if (! empty($mission['description'])) : ?>
                        <p><?= nl2br(esc((string) $mission['description']), false) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($badges !== []) : ?>
                <div class="badges">
                    <?php foreach ($badges as $badge) : ?>
                    <div class="badge">
                        <?= view('partials/about_us_badge_icon', ['iconKey' => $badge['icon_key'] ?? '']) ?>
                        <?= esc((string) ($badge['title'] ?? '')) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="company-stack">
                <?php foreach ($stackCards as $i => $card) : ?>
                    <?php if ($i > 0) : ?>
                        <div class="divider-h"><span></span></div>
                    <?php endif; ?>
                    <?= view('partials/about_us_company_card', ['row' => $card]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

</div>

<?= view('partials/site_footer') ?>

</body>
</html>
