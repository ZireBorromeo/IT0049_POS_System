<div class="page-heading"><div><h1>Products</h1><p class="muted">Manage products, prices, images, and inventory.</p></div><a class="btn primary" href="<?= site_url('products/create') ?>">＋ Add product</a></div>
<section class="panel">
<?php if (empty($products)): ?><p class="empty-state">No products yet. Add your first product.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Image</th><th>Product</th><th>Price</th><th>In stock</th><th>Created</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($products as $product): ?><tr>
<td><?php if ($product['image']): ?><img class="thumb" src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>" alt="<?= esc($product['name']) ?>"><?php else: ?><span class="no-image">—</span><?php endif; ?></td>
<td><strong><?= esc($product['name']) ?></strong></td><td>₱<?= number_format((float) $product['price'], 2) ?></td><td><span class="pill <?= (int)$product['stock_quantity'] < 5 ? 'warning' : 'normal' ?>"><?= esc($product['stock_quantity']) ?></span></td><td><?= esc($product['created_at']) ?></td>
<td class="actions"><a class="btn small" href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a><form method="post" action="<?= site_url('products/delete/' . $product['id']) ?>" onsubmit="return confirm('Delete this product?')"><?= csrf_field() ?><button class="btn small danger" type="submit">Delete</button></form></td>
</tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
</section>
