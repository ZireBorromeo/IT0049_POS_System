<div class="page-heading"><div><h1>Sales History</h1><p class="muted">A record of completed transactions.</p></div><a class="btn primary" href="<?= site_url('sales/create') ?>">＋ Record a sale</a></div>
<section class="panel">
<?php if (empty($sales)): ?><p class="empty-state">No sales recorded yet.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Sale #</th><th>Date</th><th>Product</th><th>Customer</th><th>Staff member</th><th>Qty</th><th>Total</th></tr></thead><tbody>
<?php foreach ($sales as $sale): ?><tr><td>#<?= esc($sale['id']) ?></td><td><?= esc($sale['created_at']) ?></td><td><?= esc($sale['product_name']) ?></td><td><?= esc($sale['customer_name'] ?? 'Walk-in') ?></td><td><?= esc($sale['staff_name']) ?></td><td><?= esc($sale['quantity']) ?></td><td><strong>₱<?= number_format((float)$sale['total_price'], 2) ?></strong></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
</section>
