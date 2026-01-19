<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Manage Categories</h1>

<div style="margin-bottom: 2rem; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 10px;">
    <h3>Add New Category</h3>
    <form action="<?= BASE_URL ?>/admin/add_category" method="POST"
        style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label>Category Name</label>
            <input type="text" name="name" required style="margin-bottom: 0;">
        </div>
        <button type="submit" class="btn">Add</button>
    </form>
</div>

<table class="orders-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['categories'] as $c): ?>
            <tr>
                <td>
                    <?= $c['category_id'] ?>
                </td>
                <td>
                    <?= htmlspecialchars($c['name']) ?>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/edit_category/<?= $c['category_id'] ?>" style="color: #d4af37;">Edit</a>
                    |
                    <a href="<?= BASE_URL ?>/admin/delete_category/<?= $c['category_id'] ?>" style="color: #ff4d4d;"
                        onclick="return confirm('Are you sure? This may affect products in this category.')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../app/views/layouts/footer.php'; ?>