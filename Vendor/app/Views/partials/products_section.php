<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $productsHeading */
/** @var list<array<string, mixed>> $productCards */
/** @var bool $productsUnavailable */
/** @var bool $productsSchemaMissing */

$productsHeading       = $productsHeading ?? null;
$productCards          = $productCards ?? [];
$productsUnavailable   = ($productsUnavailable ?? false) === true;
$productsSchemaMissing = ($productsSchemaMissing ?? false) === true;

$allowedStyles = \App\Controllers\Admin\ProductsItems::CARD_STYLES;

?>
<section class="block dark" id="products">
    <div class="wrap">
        <?php if ($productsSchemaMissing || $productsUnavailable) : ?>
            <?= view('partials/public_section_fallback', [
                'sectionTitle'       => 'Products & services',
                'schemaMissing'      => $productsSchemaMissing,
                'contentUnavailable' => $productsUnavailable,
                'installed'          => $installed ?? false,
                'manageUrl'          => site_url('DashBoard/Products'),
                'manageLabel'        => 'Manage Products & services page',
                'headingTag'         => 'h2',
            ]) ?>
        <?php else : ?>
            <?php if ($productsHeading !== null) : ?>
                <div class="sec-head">
                    <h2><?= esc((string) ($productsHeading['title'] ?? '')) ?></h2>
                    <?php if (! empty($productsHeading['description'])) : ?>
                        <p><?= nl2br(esc((string) $productsHeading['description']), false) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($productCards !== []) : ?>
                <div class="prod-grid">
                    <?php foreach ($productCards as $card) : ?>
                        <?php
                        $href = trim((string) ($card['href'] ?? ''));
                        $st   = (string) ($card['card_style'] ?? '');
                        if (! in_array($st, $allowedStyles, true)) {
                            $st = 'grad-blue';
                        }
                        $cardClasses = 'prod-card ' . $st;
                        ?>
                        <?php if ($href !== '') : ?>
                            <a class="<?= esc($cardClasses, 'attr') ?>" href="<?= esc($href) ?>" target="_blank" rel="noopener noreferrer">
                        <?php else : ?>
                            <div class="<?= esc($cardClasses, 'attr') ?>">
                        <?php endif; ?>
                                <div class="prod-card-head">
                                    <?php if (! empty($card['icon_svg'])) : ?>
                                        <?= $card['icon_svg'] ?>
                                    <?php endif; ?>
                                    <h3><?= esc((string) ($card['title'] ?? '')) ?></h3>
                                </div>
                                <?php if (! empty($card['description'])) : ?>
                                    <p><?= nl2br(esc((string) $card['description']), false) ?></p>
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
                                <?php if (! empty($card['sub_line'])) : ?>
                                    <p class="sub"><?= esc((string) $card['sub_line']) ?></p>
                                <?php endif; ?>
                        <?php if ($href !== '') : ?>
                            </a>
                        <?php else : ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
