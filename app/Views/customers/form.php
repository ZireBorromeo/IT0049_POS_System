<div class="page-heading"><div><h1><?= esc($pageTitle) ?></h1><p class="muted">Enter customer contact details.</p></div><a class="btn" href="<?= site_url('customers') ?>">← Back to customers</a></div>
<section class="panel form-panel"><form method="post" action="<?= current_url() ?>"><?= csrf_field() ?><div class="form-grid">
<div class="field full-width"><label for="full_name">Full name *</label><input id="full_name" name="full_name" maxlength="100" value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>" required></div>
<div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" maxlength="100" value="<?= esc(old('email', $customer['email'] ?? '')) ?>" required></div>
<div class="field"><label for="phone">Phone</label><input id="phone" name="phone" maxlength="20" value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"></div>
</div><div class="form-actions"><button class="btn primary" type="submit">Save customer</button><a class="btn" href="<?= site_url('customers') ?>">Cancel</a></div></form></section>
