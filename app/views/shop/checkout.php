<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Checkout</h1>

<div class="auth-form" style="max-width: 600px;">
    <h3>Shipping Information</h3>
    <form action="<?= BASE_URL ?>/shop/checkout" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="<?= $_SESSION['name'] ?>" disabled style="background: #eee;">
        </div>

        <div class="form-group">
            <label for="address">Shipping Address</label>
            <textarea name="address" id="address" rows="4" required
                style="width: 100%; padding: 0.8rem; border: 1px solid #ddd;"></textarea>
        </div>

        <div class="form-group">
            <label>Payment Method</label>
            <select style="width: 100%; padding: 0.8rem;">
                <option>PayPal (Mock)</option>
                <option>Credit Card (Mock)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-block">Place Order</button>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>