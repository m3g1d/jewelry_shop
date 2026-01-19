<?php require_once '../app/views/layouts/header.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Manage Products</h1>
    <a href="<?= BASE_URL ?>/admin/add_product" class="btn">Add New Product</a>
</div>

<table class="orders-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['products'] as $p): ?>
            <tr>
                <td>
                    <?= $p['product_id'] ?>
                </td>
                <td><img src="<?= $p['image_url'] ?>"
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                <td>
                    <?= htmlspecialchars($p['name']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($p['category_name']) ?>
                </td>
                <td>
                    <?= number_format($p['price'], 2) ?>€
                </td>
                <td>
                    <?= $p['stock_quantity'] ?>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/edit_product/<?= $p['product_id'] ?>" style="color: #d4af37;">Edit</a> |
                    <a href="<?= BASE_URL ?>/admin/delete_product/<?= $p['product_id'] ?>" style="color: #ff4d4d;"
                        onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../app/views/layouts/footer.php'; ?>