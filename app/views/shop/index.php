<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Our Collection</h1>

<div class="categories-bar">
    <a href="<?= BASE_URL ?>/shop" class="btn <?= !$data['active_category'] ? 'active' : '' ?>">All</a>
    <?php foreach ($data['categories'] as $cat): ?>
        <a href="<?= BASE_URL ?>/shop/index/<?= $cat['category_id'] ?>"
            class="btn <?= $data['active_category'] == $cat['category_id'] ? 'active' : '' ?>">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="product-grid"
    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
    <?php foreach ($data['products'] as $product): ?>
        <div class="product-card" style="border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
            <div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                <?php if ($product['image_url']): ?>
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                        style="max-height: 100%; max-width: 100%;">
                <?php else: ?>
                    <span>No Image</span>
                <?php endif; ?>
            </div>
            <h3>
                <?= htmlspecialchars($product['name']) ?>
            </h3>
            <p>
                <?= htmlspecialchars($product['category_name']) ?>
            </p>
            <p><strong>$
                    <?= number_format($product['price'], 2) ?>
                </strong></p>
            <a href="<?= BASE_URL ?>/shop/product/<?= $product['product_id'] ?>" class="btn btn-block">View Details</a>
        </div>
    <?php endforeach; ?>
    <?php if (empty($data['products'])): ?>
        <p>No products found in this category.</p>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>