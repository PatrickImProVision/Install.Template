<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $techHeading */
/** @var list<array<string, mixed>> $techCards */
/** @var bool $techUnavailable */
/** @var bool $techSchemaMissing */

$techHeading         = $techHeading ?? null;
$techCards           = $techCards ?? [];
$techUnavailable     = ($techUnavailable ?? false) === true;
$techSchemaMissing   = ($techSchemaMissing ?? false) === true;

?>
<section class="block tech-stack-section tech-stack-page-section" id="technology">
    <div class="wrap">
        <?php if ($techSchemaMissing || $techUnavailable) : ?>
            <?= view('partials/public_section_fallback', [
                'sectionTitle'       => 'Technology stack',
                'schemaMissing'      => $techSchemaMissing,
                'contentUnavailable' => $techUnavailable,
                'installed'          => $installed ?? false,
                'manageUrl'          => site_url('DashBoard/Tech_Stack'),
                'manageLabel'        => 'Manage Technology stack page',
                'headingTag'         => 'h2',
            ]) ?>
        <?php else : ?>
            <?php if ($techHeading !== null) : ?>
                <div class="sec-head">
                    <h2><?= esc((string) ($techHeading['title'] ?? '')) ?></h2>
                    <?php if (! empty($techHeading['description'])) : ?>
                        <p><?= nl2br(esc((string) $techHeading['description']), false) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($techCards !== []) : ?>
                <div class="tech-grid">
                    <?php foreach ($techCards as $card) : ?>
                        <?php
                        $href      = trim((string) ($card['href'] ?? ''));
                        $iconColor = trim((string) ($card['icon_color'] ?? ''));
                        $nameColor = trim((string) ($card['name_color'] ?? ''));
                        ?>
                        <div class="tech-item">
                            <?php if ($href !== '') : ?>
                                <a href="<?= esc($href) ?>" target="_blank" rel="noopener noreferrer">
                            <?php else : ?>
                                <div class="tech-item-body">
                            <?php endif; ?>
                                    <div class="tech-item-top">
                                        <?php if (! empty($card['icon_svg'])) : ?>
                                            <span class="tech-item-icon"<?php if ($iconColor !== '') : ?> style="color: <?= esc($iconColor, 'css') ?>;"<?php endif; ?>><?= $card['icon_svg'] ?></span>
                                        <?php endif; ?>
                                        <div>
                                            <?php if (! empty($card['category'])) : ?>
                                                <strong><?= esc((string) $card['category']) ?></strong>
                                            <?php endif; ?>
                                            <?php if (! empty($card['product_name'])) : ?>
                                                <span class="tech-name"<?php if ($nameColor !== '') : ?> style="color: <?= esc($nameColor, 'css') ?>;"<?php endif; ?>><?= esc((string) $card['product_name']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (! empty($card['blurb'])) : ?>
                                        <p><?= nl2br(esc((string) $card['blurb']), false) ?></p>
                                    <?php endif; ?>
                            <?php if ($href !== '') : ?>
                                </a>
                            <?php else : ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
