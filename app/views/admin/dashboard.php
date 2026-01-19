<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Admin Dashboard</h1>

<div class="admin-dashboard-grid"
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; border: 1px solid #d4af37;">
        <h3>Product Management</h3>
        <p>Edit, Delete or Add products.</p>
        <a href="<?= BASE_URL ?>/admin/products" class="btn" style="width: 100%; margin-top: 10px;">Manage Products</a>
    </div>

    <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; border: 1px solid #d4af37;">
        <h3>Category Management</h3>
        <p>Manage product categories.</p>
        <a href="<?= BASE_URL ?>/admin/categories" class="btn" style="width: 100%; margin-top: 10px;">Manage
            Categories</a>
    </div>

    <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; border: 1px solid #d4af37;">
        <h3>Order Management</h3>
        <p>View and track all orders.</p>
        <a href="<?= BASE_URL ?>/admin/orders" class="btn" style="width: 100%; margin-top: 10px;">View Orders</a>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>