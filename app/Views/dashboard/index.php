<div class="page-heading">
    <div><h1>Dashboard</h1><p class="muted">A quick overview of your store.</p></div>
    <a class="btn primary" href="<?= site_url('sales/create') ?>">＋ Record a sale</a>
</div>
<div class="stats-grid">
    <article class="stat-card"><span class="stat-label">Products</span><strong><?= esc($productCount) ?></strong><a href="<?= site_url('products') ?>">Manage products →</a></article>
    <article class="stat-card"><span class="stat-label">Customers</span><strong><?= esc($customerCount) ?></strong><a href="<?= site_url('customers') ?>">Manage customers →</a></article>
    <article class="stat-card"><span class="stat-label">Staff accounts</span><strong><?= esc($staffCount) ?></strong><a href="<?= site_url('staff') ?>">Manage staff →</a></article>
</div>
<section class="panel">
    <div class="panel-heading"><h2>Recent sales</h2><a href="<?= site_url('sales/history') ?>">View all →</a></div>
    <?php if (empty($sales)): ?><p class="empty-state">No sales have been recorded yet.</p><?php else: ?>
    <div class="table-wrap"><table><thead><tr><th>Date</th><th>Product</th><th>Customer</th><th>Staff</th><th>Qty</th><th>Total</th></tr></thead><tbody>
    <?php foreach ($sales as $sale): ?><tr><td><?= esc($sale['created_at']) ?></td><td><?= esc($sale['product_name']) ?></td><td><?= esc($sale['customer_name'] ?? 'Walk-in') ?></td><td><?= esc($sale['staff_name']) ?></td><td><?= esc($sale['quantity']) ?></td><td>₱<?= number_format((float) $sale['total_price'], 2) ?></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
</section>
