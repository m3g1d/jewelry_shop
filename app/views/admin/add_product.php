<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Add New Product</h1>

<div class="auth-form" style="max-width: 600px;">
    <form action="<?= BASE_URL ?>/admin/add_product" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required style="width: 100%; padding: 0.8rem;">
                <?php foreach ($data['categories'] as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" style="width: 100%; padding: 0.8rem;"></textarea>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" required>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock" required>
        </div>

        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>