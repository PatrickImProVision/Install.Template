<?= $this->extend('install/layout') ?>

<?= $this->section('content') ?>
<h1>Site identity</h1>
<p class="sub">Public name and description for your installation.</p>

<?= form_open(site_url('install/site')) ?>

<div class="form-grid">
    <div class="stack full">
        <label for="site_name">Site name</label>
        <input type="text" name="site_name" id="site_name" value="<?= esc(old('site_name')) ?>" required maxlength="255">
    </div>
    <div class="stack full">
        <label for="site_description">Description</label>
        <textarea name="site_description" id="site_description" maxlength="5000" rows="4"><?= esc(old('site_description')) ?></textarea>
    </div>
</div>

<div class="actions">
    <button type="submit" class="primary">Finish installation</button>
</div>

<?= form_close() ?>
<?= $this->endSection() ?>
