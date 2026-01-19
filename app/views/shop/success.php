<?php require_once '../app/views/layouts/header.php'; ?>

<div style="text-align: center; padding: 50px;">
    <h1 style="color: green;">Order Placed Successfully!</h1>
    <p>Thank you for your purchase.</p>
    <p>Order ID: #
        <?= $data['order_id'] ?>
    </p>
    <br>
    <a href="<?= BASE_URL ?>/shop" class="btn">Continue Shopping</a>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>