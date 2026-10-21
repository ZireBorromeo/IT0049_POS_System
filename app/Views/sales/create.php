<div class="page-heading"><div><h1>Record a Sale</h1><p class="muted">Choose a product and quantity. Inventory is updated when the sale is saved.</p></div><a class="btn" href="<?= site_url('sales/history') ?>">Sales history →</a></div>
<section class="panel form-panel">
<?php if (empty($products)): ?><div class="alert error">There are no products with available stock. Add products or update inventory before recording a sale.</div><?php else: ?>
<form method="post" action="<?= site_url('sales/create') ?>"><?= csrf_field() ?>
<div class="form-grid">
<div class="field full-width"><label for="product_id">Product *</label><select id="product_id" name="product_id" required><option value="">Select a product</option><?php foreach ($products as $product): ?><option value="<?= $product['id'] ?>" data-price="<?= esc($product['price']) ?>" data-stock="<?= esc($product['stock_quantity']) ?>" <?= old('product_id') == $product['id'] ? 'selected' : '' ?>><?= esc($product['name']) ?> — ₱<?= number_format((float)$product['price'], 2) ?> (stock: <?= esc($product['stock_quantity']) ?>)</option><?php endforeach; ?></select><p class="hint" id="stock-hint">Select a product to see its available stock.</p></div>
<div class="field"><label for="customer_id">Customer (optional)</label><select id="customer_id" name="customer_id"><option value="">Walk-in customer</option><?php foreach ($customers as $customer): ?><option value="<?= $customer['id'] ?>" <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>><?= esc($customer['full_name']) ?></option><?php endforeach; ?></select></div>
<div class="field"><label for="quantity">Quantity *</label><input id="quantity" name="quantity" type="number" min="1" step="1" value="<?= esc(old('quantity', '1')) ?>" required></div>
<div class="field full-width total-preview"><span>Estimated total</span><strong id="total-preview">₱0.00</strong><p class="hint">The server recalculates the total and validates stock before saving.</p></div>
</div>
<div class="form-actions"><button class="btn primary" type="submit">Complete sale</button><a class="btn" href="<?= site_url('dashboard') ?>">Cancel</a></div>
</form>
<script>
(function () {
 const product = document.getElementById('product_id');
 const quantity = document.getElementById('quantity');
 const total = document.getElementById('total-preview');
 const hint = document.getElementById('stock-hint');
 function update() {
   const option = product.options[product.selectedIndex];
   const price = Number(option && option.dataset.price || 0);
   const stock = Number(option && option.dataset.stock || 0);
   const qty = Number(quantity.value || 0);
   total.textContent = '₱' + (price * qty).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
   hint.textContent = option && option.value ? 'Available stock: ' + stock + (qty > stock ? ' — quantity exceeds stock!' : '') : 'Select a product to see its available stock.';
 }
 product.addEventListener('change', update);
 quantity.addEventListener('input', update);
 update();
})();
</script>
<?php endif; ?>
</section>
