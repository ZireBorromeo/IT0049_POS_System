<div class="page-heading"><div><h1><?= esc($pageTitle) ?></h1><p class="muted">Enter the product information below.</p></div><a class="btn" href="<?= site_url('products') ?>">← Back to products</a></div>
<section class="panel form-panel">
<form method="post" enctype="multipart/form-data" action="<?= current_url() ?>">
<?= csrf_field() ?>
<div class="form-grid">
<div class="field full-width"><label for="name">Product name *</label><input id="name" name="name" maxlength="100" value="<?= esc(old('name', $product['name'] ?? '')) ?>" required></div>
<div class="field"><label for="price">Price (₱) *</label><input id="price" name="price" type="number" min="0" step="0.01" value="<?= esc(old('price', $product['price'] ?? '')) ?>" required></div>
<div class="field"><label for="stock_quantity">Stock quantity *</label><input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? '0')) ?>" required></div>
<div class="field full-width"><label for="image">Product image (JPG, PNG, or WebP; max 2 MB)</label><input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"><?php if (! empty($product['image'])): ?><p class="hint">Current image: <?= esc($product['image']) ?></p><?php endif; ?></div>
</div>
<div class="form-actions"><button class="btn primary" type="submit">Save product</button><a class="btn" href="<?= site_url('products') ?>">Cancel</a></div>
</form></section>
