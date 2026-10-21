<section class="login-card">
    <div class="login-mark">POS</div>
    <h1>Staff Login</h1>
    <p class="muted">Sign in to manage products, customers, and sales.</p>
    <?php if (! empty($error)): ?><div class="alert error"><?= esc($error) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input id="username" name="username" value="<?= esc(old('username')) ?>" required autocomplete="username">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        <button class="btn primary full" type="submit">Log in</button>
    </form>
    <p class="hint">First time? Import the database and run the setup seeder. See README.md.</p>
</section>
