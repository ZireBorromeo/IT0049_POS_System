<div class="page-heading"><div><h1>Customers</h1><p class="muted">Maintain your customer list.</p></div><a class="btn primary" href="<?= site_url('customers/create') ?>">＋ Add customer</a></div>
<section class="panel">
<?php if (empty($customers)): ?><p class="empty-state">No customers yet. You can still record walk-in sales.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Created</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($customers as $customer): ?><tr><td><strong><?= esc($customer['full_name']) ?></strong></td><td><?= esc($customer['email']) ?></td><td><?= esc($customer['phone'] ?? '—') ?></td><td><?= esc($customer['created_at']) ?></td><td class="actions"><a class="btn small" href="<?= site_url('customers/edit/' . $customer['id']) ?>">Edit</a><form method="post" action="<?= site_url('customers/delete/' . $customer['id']) ?>" onsubmit="return confirm('Delete this customer?')"><?= csrf_field() ?><button class="btn small danger" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
</section>
