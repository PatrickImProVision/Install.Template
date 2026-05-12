<?= $this->extend('install/layout') ?>

<?= $this->section('content') ?>
<h1>Administrator</h1>
<p class="sub">Create the first administrator account for this site.</p>

<?= form_open(site_url('install/admin')) ?>

<div class="form-grid">
    <div class="stack full">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= esc(old('email')) ?>" required autocomplete="email">
    </div>
    <div class="stack">
        <label for="password">Password</label>
        <div class="pw-field">
            <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password">
            <button type="button" class="pw-toggle" data-pw-target="password" aria-label="Show password" aria-pressed="false">Show</button>
        </div>
    </div>
    <div class="stack">
        <label for="password_confirm">Confirm password</label>
        <div class="pw-field">
            <input type="password" name="password_confirm" id="password_confirm" required minlength="8" autocomplete="new-password">
            <button type="button" class="pw-toggle" data-pw-target="password_confirm" aria-label="Show confirm password" aria-pressed="false">Show</button>
        </div>
    </div>
</div>

<div class="actions">
    <button type="submit" class="primary">Create administrator</button>
</div>

<?= form_close() ?>

<script>
(function () {
    document.querySelectorAll('.pw-toggle[data-pw-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-pw-target');
            var input = id ? document.getElementById(id) : null;
            if (!input) {
                return;
            }
            var showing = input.type === 'password';
            input.type = showing ? 'text' : 'password';
            btn.textContent = showing ? 'Hide' : 'Show';
            btn.setAttribute('aria-pressed', showing ? 'true' : 'false');
            var base = id === 'password_confirm' ? 'confirm password' : 'password';
            btn.setAttribute('aria-label', showing ? 'Hide ' + base : 'Show ' + base);
        });
    });
})();
</script>
<?= $this->endSection() ?>
