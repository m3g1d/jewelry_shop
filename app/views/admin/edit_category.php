<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Edit Category:
    <?= htmlspecialchars($data['category']['name']) ?>
</h1>

<div class="auth-form" style="max-width: 500px;">
    <form action="<?= BASE_URL ?>/admin/edit_category/<?= $data['category']['category_id'] ?>" method="POST">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($data['category']['name']) ?>" required>
        </div>
        <button type="submit" class="btn">Update Category</button>
        <a href="<?= BASE_URL ?>/admin/categories" style="margin-left: 1rem; color: #888;">Cancel</a>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>