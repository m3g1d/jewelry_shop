<?php require_once '../app/views/layouts/header.php'; ?>

<h1>Order Management</h1>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="border-bottom: 2px solid #ddd; background: #f4f4f4;">
            <th style="padding: 10px; text-align: left;">Order ID</th>
            <th style="padding: 10px; text-align: left;">Customer</th>
            <th style="padding: 10px; text-align: left;">Date</th>
            <th style="padding: 10px; text-align: left;">Total</th>
            <th style="padding: 10px; text-align: left;">Status</th>
            <th style="padding: 10px; text-align: left;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['orders'] as $order): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">#
                    <?= $order['order_id'] ?>
                </td>
                <td style="padding: 10px;">
                    <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?>
                </td>
                <td style="padding: 10px;">
                    <?= $order['order_date'] ?>
                </td>
                <td style="padding: 10px;">$
                    <?= number_format($order['total_amount'], 2) ?>
                </td>
                <td style="padding: 10px;">
                    <span style="padding: 5px 10px; border-radius: 15px; background: #e0f7fa; font-size: 0.9em;">
                        <?= $order['order_status'] ?>
                    </span>
                </td>
                <td style="padding: 10px;">
                    <form action="<?= BASE_URL ?>/admin/update_order_status" method="POST" style="display: flex; gap: 5px;">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <select name="status" style="padding: 5px;">
                            <option value="New" <?= $order['order_status'] == 'New' ? 'selected' : '' ?>>New</option>
                            <option value="Processing" <?= $order['order_status'] == 'Processing' ? 'selected' : '' ?>
                                >Processing</option>
                            <option value="Shipped" <?= $order['order_status'] == 'Shipped' ? 'selected' : '' ?>>Shipped
                            </option>
                            <option value="Delivered" <?= $order['order_status'] == 'Delivered' ? 'selected' : '' ?>>Delivered
                            </option>
                            <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled
                            </option>
                        </select>
                        <button type="submit" class="btn" style="padding: 5px 10px; font-size: 0.8em;">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../app/views/layouts/footer.php'; ?>