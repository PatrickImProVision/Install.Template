<?php

declare(strict_types=1);

/** @var list<array<string, mixed>> $contactRows */

$intro   = array_values(array_filter($contactRows, static fn (array $r): bool => ($r['kind'] ?? '') === 'brand'));
$company = array_values(array_filter($contactRows, static fn (array $r): bool => ($r['column_group'] ?? '') === 'company'));
$contact = array_values(array_filter($contactRows, static fn (array $r): bool => ($r['column_group'] ?? '') === 'contact'));
$legal   = array_values(array_filter($contactRows, static fn (array $r): bool => ($r['kind'] ?? '') === 'legal'));

?>
    <div class="foot-wide foot-grid">
        <div>
            <?php foreach ($intro as $row) : ?>
                <h3><?= esc((string) ($row['title'] ?? '')) ?></h3>
                <?php if (! empty($row['description'])) : ?>
                    <p><?= nl2br(esc((string) $row['description']), false) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div>
            <?php
            $companyEntryIdx = 0;
            foreach ($company as $row) :
                $kind = (string) ($row['kind'] ?? '');
                if ($kind === 'column_heading') : ?>
                    <h4><?= esc((string) ($row['title'] ?? '')) ?></h4>
                <?php elseif ($kind === 'company_entry') :
                    $companyEntryIdx++;
                    $mt  = $companyEntryIdx > 1 ? ' style="margin-top:1rem;"' : '';
                    $href  = trim((string) ($row['href'] ?? ''));
                    $meta  = (string) ($row['meta_label'] ?? '');
                    $title = (string) ($row['title'] ?? '');
                    if ($href !== '') : ?>
                        <p<?= $mt ?>><a href="<?= esc($href) ?>" target="_blank" rel="noopener noreferrer" style="color:#e2e8f0;"><?= esc($title) ?></a><?php if ($meta !== '') : ?><br><span class="foot-meta"><?= esc($meta) ?></span><?php endif; ?></p>
                    <?php else : ?>
                        <p<?= $mt ?>><strong style="color:#e2e8f0;"><?= esc($title) ?></strong><?php if ($meta !== '') : ?><br><span class="foot-meta"><?= esc($meta) ?></span><?php endif; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="footer-contact-col">
            <?php
            $contactHrefIdx = 0;
            foreach ($contact as $row) :
                $kind = (string) ($row['kind'] ?? '');
                if ($kind === 'column_heading') : ?>
                    <h4><?= esc((string) ($row['title'] ?? '')) ?></h4>
                <?php elseif ($kind === 'contact_entry') :
                    $href    = trim((string) ($row['href'] ?? ''));
                    $metaTag = (string) ($row['meta_label'] ?? '');
                    $title   = (string) ($row['title'] ?? '');
                    if ($href !== '') :
                        $contactHrefIdx++;
                        $ext = str_starts_with($href, 'http');
                        $mt  = match ($contactHrefIdx) {
                            1 => '',
                            2 => ' style="margin-top:1rem;"',
                            3 => ' style="margin-top:0.5rem;"',
                            default => ' style="margin-top:0.75rem;"',
                        };
                        ?>
                        <p<?= $mt ?>><a href="<?= esc($href) ?>"<?= $ext ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= esc($title) ?></a></p>
                    <?php elseif ($metaTag === 'meta') : ?>
                        <p class="foot-meta" style="margin-top:0.75rem;"><?= esc($title) ?></p>
                    <?php else : ?>
                        <p><?= esc($title) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="border-top foot-wide">
        <?php foreach ($legal as $row) : ?>
            <?php
            $d = (string) ($row['description'] ?? '');
            if ($d === '') {
                continue;
            }
            $d = str_replace('{year}', (string) date('Y'), $d);
            ?>
            <p><?= esc($d) ?></p>
        <?php endforeach; ?>
    </div>
