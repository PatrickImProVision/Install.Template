<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $servicesHeading */
/** @var list<array<string, mixed>> $serviceCards */
/** @var bool $servicesUnavailable */
/** @var bool $servicesSchemaMissing */

$servicesHeading       = $servicesHeading ?? null;
$serviceCards          = $serviceCards ?? [];
$servicesUnavailable   = ($servicesUnavailable ?? false) === true;
$servicesSchemaMissing = ($servicesSchemaMissing ?? false) === true;

?>
<section class="block muted services-page-section" id="services">
    <div class="wrap">
        <?php if ($servicesSchemaMissing || $servicesUnavailable) : ?>
            <?= view('partials/public_section_fallback', [
                'sectionTitle'       => 'Services',
                'schemaMissing'      => $servicesSchemaMissing,
                'contentUnavailable' => $servicesUnavailable,
                'installed'          => $installed ?? false,
                'manageUrl'          => site_url('DashBoard/Services'),
                'manageLabel'        => 'Manage Services page',
                'headingTag'         => 'h2',
            ]) ?>
        <?php else : ?>
            <?php if ($servicesHeading !== null) : ?>
                <div class="sec-head">
                    <h2><?= esc((string) ($servicesHeading['title'] ?? '')) ?></h2>
                    <?php if (! empty($servicesHeading['description'])) : ?>
                        <p><?= nl2br(esc((string) $servicesHeading['description']), false) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($serviceCards !== []) : ?>
                <div class="svc-grid">
                    <?php foreach ($serviceCards as $card) : ?>
                        <article class="svc-card">
                            <div class="svc-card-visual">
                                <?php if (! empty($card['image_url'])) : ?>
                                    <img src="<?= esc((string) $card['image_url']) ?>" alt="<?= esc((string) ($card['image_alt'] ?? '')) ?>" width="1080" height="720" loading="lazy">
                                <?php endif; ?>
                                <?php if (! empty($card['icon_svg'])) : ?>
                                    <div class="svc-card-float-icon" aria-hidden="true"><?= $card['icon_svg'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="svc-body">
                                <h3><?= esc((string) ($card['title'] ?? '')) ?></h3>
                                <?php if (! empty($card['description'])) : ?>
                                    <p class="desc"><?= nl2br(esc((string) $card['description']), false) ?></p>
                                <?php endif; ?>
                                <?php
                                $rawBullets = trim((string) ($card['bullets'] ?? ''));
                                if ($rawBullets !== '') :
                                    $lines = preg_split('/\R/u', $rawBullets) ?: [];
                                    ?>
                                    <ul>
                                        <?php foreach ($lines as $line) : ?>
                                            <?php
                                            $line = trim((string) $line);
                                            if ($line === '') {
                                                continue;
                                            }
                                            ?>
                                            <li><?= esc($line) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
