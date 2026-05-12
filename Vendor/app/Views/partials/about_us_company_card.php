<?php

declare(strict_types=1);

/** @var array<string, mixed> $row */

$row      = $row ?? [];
$style    = in_array($row['card_style'] ?? '', ['blue', 'amber'], true) ? (string) $row['card_style'] : '';
$classes  = 'company-card' . ($style !== '' ? ' ' . $style : '');
$href     = isset($row['href']) && $row['href'] !== null && $row['href'] !== '' ? (string) $row['href'] : null;
$title    = (string) ($row['title'] ?? '');
$desc     = (string) ($row['description'] ?? '');
$footnote = isset($row['footnote']) && $row['footnote'] !== null && $row['footnote'] !== '' ? (string) $row['footnote'] : null;
$rawLines = preg_split('/\R/u', trim((string) ($row['bullets'] ?? ''))) ?: [];
$bullets  = array_values(array_filter(array_map(static fn (string $s): string => trim($s), $rawLines), static fn (string $s): bool => $s !== ''));

$inner = static function () use ($title, $desc, $footnote, $bullets): void {
    echo '<h4>' . esc($title) . '</h4>';
    if ($desc !== '') {
        echo '<p>' . esc($desc) . '</p>';
    }
    if ($bullets !== []) {
        echo '<ul>';
        foreach ($bullets as $li) {
            echo '<li>' . esc($li) . '</li>';
        }
        echo '</ul>';
    }
    if ($footnote !== null) {
        echo '<p class="small">' . esc($footnote) . '</p>';
    }
};

if ($href !== null) {
    echo '<a href="' . esc($href) . '" target="_blank" rel="noopener noreferrer" class="' . esc($classes, 'attr') . '" style="text-decoration:none;color:inherit;display:block;">';
    $inner();
    echo '</a>';
} else {
    echo '<div class="' . esc($classes, 'attr') . '">';
    $inner();
    echo '</div>';
}
