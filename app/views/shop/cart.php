<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Shopping Cart</h1>

<?php if (empty($data['cart'])): ?>
    <p>Your cart is empty. <a href="<?= BASE_URL ?>/shop">Go Shopping</a></p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="text-align: left; padding: 10px;">Product</th>
                <th style="text-align: center; padding: 10px;">Price</th>
                <th style="text-align: center; padding: 10px;">Quantity</th>
                <th style="text-align: right; padding: 10px;">Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['cart'] as $item): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        <?= htmlspecialchars($item['name']) ?>
                    </td>
                    <td style="text-align: center;">$
                        <?= number_format($item['price'], 2) ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $item['qty'] ?>
                    </td>
                    <td style="text-align: right;">$
                        <?= number_format($item['line_total'], 2) ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?= BASE_URL ?>/shop/remove_from_cart/<?= $item['product_id'] ?>"
                            style="color: red; text-decoration: none;">&times;</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; padding: 10px; font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; padding: 10px; font-weight: bold;">$
                    <?= number_format($data['total'], 2) ?>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="text-align: right;">
        <a href="<?= BASE_URL ?>/shop" class="btn" style="background: #999;">Continue Shopping</a>
        <a href="<?= BASE_URL ?>/shop/checkout" class="btn">Proceed to Checkout</a>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>