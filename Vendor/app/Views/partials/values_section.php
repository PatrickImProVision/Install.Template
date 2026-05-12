<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $valuesHeading */
/** @var list<array<string, mixed>> $valueItems */
/** @var bool $valuesUnavailable */
/** @var bool $valuesSchemaMissing */

$valuesHeading       = $valuesHeading ?? null;
$valueItems          = $valueItems ?? [];
$valuesUnavailable   = ($valuesUnavailable ?? false) === true;
$valuesSchemaMissing = ($valuesSchemaMissing ?? false) === true;

?>
<section class="values-band" id="values">
    <div class="wrap">
        <?php if ($valuesSchemaMissing) : ?>
            <div class="values-card">
                <h2 id="values-title">Our values</h2>
                <p style="text-align:center;color:rgba(219,234,254,0.95);margin:0 0 1rem;">This content is not available yet.</p>
                <?php if (! empty($installed) && session()->get('user_role') === 'administrator') : ?>
                    <p style="text-align:center;color:rgba(148,163,184,0.95);max-width:38rem;margin:0 auto 1rem;line-height:1.55;font-size:0.95rem;">The database table for this section has not been created yet. Import the preset tables using the installer or the SQL files under <code style="color:#e2e8f0;">app/Database/Source/</code>, then reload this page.</p>
                    <p style="text-align:center;"><a href="<?= site_url('DashBoard/Values') ?>">Manage Our values page</a></p>
                <?php endif; ?>
            </div>
        <?php elseif ($valuesUnavailable) : ?>
            <div class="values-card">
                <h2 id="values-title">Our values</h2>
                <p style="text-align:center;color:rgba(219,234,254,0.95);margin:0 0 1rem;">No content yet — add blocks in the admin dashboard.</p>
                <?php if (! empty($installed) && session()->get('user_role') === 'administrator') : ?>
                    <p style="text-align:center;"><a href="<?= site_url('DashBoard/Values') ?>">Manage Our values page</a></p>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="values-card">
                <h2 id="values-title"><?= esc(
                    (string) (($valuesHeading !== null && ($valuesHeading['title'] ?? '') !== '')
                        ? $valuesHeading['title']
                        : 'Our values')
                ) ?></h2>
                <?php if ($valuesHeading !== null && ! empty($valuesHeading['description'])) : ?>
                    <p style="text-align:center;margin:0 0 1.5rem;color:rgba(219,234,254,0.95);max-width:42rem;margin-left:auto;margin-right:auto;"><?= nl2br(esc((string) $valuesHeading['description']), false) ?></p>
                <?php endif; ?>
                <?php if ($valueItems !== []) : ?>
                    <div class="values-grid" aria-labelledby="values-title">
                        <?php foreach ($valueItems as $item) : ?>
                            <div class="values-item">
                                <?php if (! empty($item['emoji'])) : ?>
                                    <div class="values-emoji" aria-hidden="true"><?= $item['emoji'] ?></div>
                                <?php endif; ?>
                                <h3><?= esc((string) ($item['title'] ?? '')) ?></h3>
                                <?php if (! empty($item['description'])) : ?>
                                    <p><?= nl2br(esc((string) $item['description']), false) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
