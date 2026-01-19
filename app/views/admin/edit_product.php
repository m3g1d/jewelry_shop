<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Edit Product:
    <?= htmlspecialchars($data['product']['name']) ?>
</h1>

<div class="auth-form" style="max-width: 600px;">
    <form action="<?= BASE_URL ?>/admin/edit_product/<?= $data['product']['product_id'] ?>" method="POST">
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required style="width: 100%; padding: 0.8rem;">
                <?php foreach ($data['categories'] as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $data['product']['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['product']['name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"
                style="width: 100%; padding: 0.8rem;"><?= htmlspecialchars($data['product']['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?= $data['product']['price'] ?>" required>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" value="<?= $data['product']['stock_quantity'] ?>" required>
        </div>

        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" value="<?= htmlspecialchars($data['product']['image_url']) ?>">
        </div>

        <button type="submit" class="btn">Update Product</button>
        <a href="<?= BASE_URL ?>/admin/products" style="margin-left: 1rem; color: #888;">Cancel</a>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>