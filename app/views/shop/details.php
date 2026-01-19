<?php require_once '../app/views/layouts/header.php'; ?>

<div class="product-details" style="display: flex; gap: 40px; margin-top: 20px;">
    <div
        style="flex: 1; border: 1px solid #ddd; height: 400px; display: flex; align-items: center; justify-content: center;">
        <?php if ($data['product']['image_url']): ?>
            <img src="<?= htmlspecialchars($data['product']['image_url']) ?>" style="max-width: 100%; max-height: 100%;">
        <?php else: ?>
            <span>No Image</span>
        <?php endif; ?>
    </div>

    <div style="flex: 1;">
        <h1>
            <?= htmlspecialchars($data['product']['name']) ?>
        </h1>
        <h3 style="color: var(--gold-color);">$
            <?= number_format($data['product']['price'], 2) ?>
        </h3>
        <p>Category:
            <?= htmlspecialchars($data['product']['category_name']) ?>
        </p>
        <p>
            <?= nl2br(htmlspecialchars($data['product']['description'])) ?>
        </p>

        <?php if ($data['product']['stock_quantity'] > 0): ?>
            <form action="<?= BASE_URL ?>/shop/add_to_cart" method="POST">
                <input type="hidden" name="product_id" value="<?= $data['product']['product_id'] ?>">
                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" max="<?= $data['product']['stock_quantity'] ?>"
                        style="width: 100px;">
                </div>
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">Out of Stock</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>