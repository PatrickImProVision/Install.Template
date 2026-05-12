<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $contactPageHeading */
/** @var bool $contactUnavailable */
/** @var bool $contactSchemaMissing */

$contactUnavailable    = ($contactUnavailable ?? false) === true;
$contactSchemaMissing  = ($contactSchemaMissing ?? false) === true;

?>
<?php if ($contactSchemaMissing || $contactUnavailable) : ?>
    <section class="block muted contact-page-hero-section">
        <div class="wrap">
            <?= view('partials/public_section_fallback', [
                'sectionTitle'       => 'Contact',
                'schemaMissing'      => $contactSchemaMissing,
                'contentUnavailable' => $contactUnavailable,
                'installed'          => $installed ?? false,
                'manageUrl'          => site_url('DashBoard/Site_Contacts'),
                'manageLabel'        => 'Manage Contact & footer',
                'emptyLead'          => 'No footer content yet — import preset tables or add blocks in the admin dashboard.',
                'headingTag'         => 'h1',
            ]) ?>
        </div>
    </section>
<?php else : ?>
    <?php
    $h = $contactPageHeading ?? null;
    $heroTitle = ($h !== null && ($h['title'] ?? '') !== '') ? (string) $h['title'] : 'Contact';
    ?>
    <section class="block white contact-page-hero-section">
        <div class="wrap">
            <div class="sec-head">
                <h1 style="font-size:2rem;margin-bottom:0.5rem;"><?= esc($heroTitle) ?></h1>
                <?php if ($h !== null && ! empty($h['description'])) : ?>
                    <p><?= nl2br(esc((string) $h['description']), false) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
