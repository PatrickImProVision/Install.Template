<?php

declare(strict_types=1);

helper('url');

/** @var list<array<string, mixed>>|null $contactRows */

$contactTableMissing = false;

try {
    $contactTableMissing = ! \Config\Database::connect()->tableExists('contact_items');
} catch (\Throwable) {
    $contactTableMissing = true;
}

if (! isset($contactRows)) {
    $contactRows = [];
    if (! $contactTableMissing) {
        try {
            $contactRows = model(\App\Models\ContactItemModel::class)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();
        } catch (\Throwable) {
            $contactRows = [];
        }
    }
}

?>
<footer class="site" id="contact">
<?php if ($contactTableMissing || $contactRows === []) : ?>
    <div class="foot-wide">
        <?php if ($contactTableMissing) : ?>
            <p style="margin:0;color:#94a3b8;">Footer content is not available yet.</p>
            <?php if (session()->get('user_role') === 'administrator') : ?>
                <p style="margin:0.85rem 0 0;font-size:0.9rem;color:#cbd5e1;line-height:1.55;max-width:40rem;">The <code>contact_items</code> table has not been created. Import the preset database tables from the installer or the scripts under <code>app/Database/Source/</code>, then reload the site.</p>
                <p style="margin:0.75rem 0 0;"><a href="<?= site_url('DashBoard/Site_Contacts') ?>">Open Contact &amp; footer (CMS)</a></p>
            <?php endif; ?>
        <?php else : ?>
            <p style="margin:0;color:#94a3b8;">Footer content has not been configured yet.</p>
            <?php if (session()->get('user_role') === 'administrator') : ?>
                <p style="margin:0.85rem 0 0;font-size:0.9rem;color:#cbd5e1;line-height:1.55;max-width:40rem;">Add blocks in Contact &amp; footer (CMS), then reload the site.</p>
                <p style="margin:0.75rem 0 0;"><a href="<?= site_url('DashBoard/Site_Contacts') ?>">Open Contact &amp; footer (CMS)</a></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="border-top foot-wide">
        <p>© <?= esc(date('Y')) ?>. All rights reserved.</p>
    </div>
<?php else : ?>
<?= view('partials/contact_footer_grid', ['contactRows' => $contactRows]) ?>
<?php endif; ?>
    <div class="debug-env foot-wide">
        <p style="margin:0;">Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory. · Environment: <?= esc(ENVIRONMENT) ?></p>
    </div>
</footer>
