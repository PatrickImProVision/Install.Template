<?php

declare(strict_types=1);

/**
 * When CMS tables are missing vs empty — avoids telling visitors to "use the dashboard".
 *
 * @var string       $sectionTitle
 * @var bool         $schemaMissing       True when the backing table does not exist.
 * @var bool         $contentUnavailable  True when the table exists but has no usable rows.
 * @var bool         $installed
 * @var string|null  $manageUrl
 * @var string|null  $manageLabel
 * @var string       $emptyLead           Lead sentence when table exists but content is empty.
 * @var string       $headingTag          'h1' or 'h2'
 */

$sectionTitle       = (string) ($sectionTitle ?? 'Page');
$schemaMissing      = ($schemaMissing ?? false) === true;
$contentUnavailable = ($contentUnavailable ?? false) === true;
$installed          = ($installed ?? false) === true;
$manageUrl          = isset($manageUrl) ? trim((string) $manageUrl) : '';
$manageLabel        = (string) ($manageLabel ?? 'Manage');
$emptyLead          = (string) ($emptyLead ?? 'No content yet — add blocks in the admin dashboard.');
$headingTag         = (($headingTag ?? 'h2') === 'h1') ? 'h1' : 'h2';
?>
<?php if ($schemaMissing) : ?>
            <div class="sec-head">
                <?php if ($headingTag === 'h1') : ?>
                    <h1 style="font-size:2rem;margin-bottom:0.5rem;"><?= esc($sectionTitle) ?></h1>
                <?php else : ?>
                    <h2><?= esc($sectionTitle) ?></h2>
                <?php endif; ?>
                <p>This content is not available yet.</p>
            </div>
            <?php if ($installed && session()->get('user_role') === 'administrator') : ?>
                <p style="text-align:center;max-width:38rem;margin-left:auto;margin-right:auto;color:#64748b;font-size:0.95rem;line-height:1.55;">The database table for this section has not been created yet. Import the preset tables using the installer (database import step) or the SQL files under <code>app/Database/Source/</code>, then reload this page.</p>
                <?php if ($manageUrl !== '') : ?>
                    <p style="text-align:center;margin-top:0.75rem;"><a href="<?= esc($manageUrl) ?>"><?= esc($manageLabel) ?></a></p>
                <?php endif; ?>
            <?php endif; ?>
<?php elseif ($contentUnavailable) : ?>
            <div class="sec-head">
                <?php if ($headingTag === 'h1') : ?>
                    <h1 style="font-size:2rem;margin-bottom:0.5rem;"><?= esc($sectionTitle) ?></h1>
                <?php else : ?>
                    <h2><?= esc($sectionTitle) ?></h2>
                <?php endif; ?>
                <p><?= esc($emptyLead) ?></p>
            </div>
            <?php if ($installed && session()->get('user_role') === 'administrator') : ?>
                <?php if ($manageUrl !== '') : ?>
                    <p style="text-align:center;"><a href="<?= esc($manageUrl) ?>"><?= esc($manageLabel) ?></a></p>
                <?php endif; ?>
            <?php endif; ?>
<?php endif; ?>
