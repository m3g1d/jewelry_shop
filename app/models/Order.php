<?php

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createOrder($userId, $cartItems, $address)
    {
        try {
            // New Schema: Orders need a shipping_address_id, not just text.
            // For this Prototype, we'll create an Address record first or simplify if allowed.
            // The requirement says "orders... shipping_address_id".
            // Let's create an address on the fly for the user for this order.

            $this->db->beginTransaction();

            // 1. Create Address (Since schema requires ID)
            $stmtAddr = $this->db->prepare("INSERT INTO addresses (user_id, street_address, city, zip_code, country) VALUES (?, ?, 'City', '0000', 'Country')");
            // Splitting the address string loosely for demo
            $stmtAddr->execute([$userId, $address]);
            $addressId = $this->db->lastInsertId();

            // 2. Calculate Total & Verify Stock
            $total = 0;
            $itemsToInsert = [];

            foreach ($cartItems as $pid => $qty) {
                // Lock row for update
                $stmt = $this->db->prepare("SELECT price, stock_quantity FROM products WHERE product_id = :id FOR UPDATE");
                $stmt->bindParam(':id', $pid);
                $stmt->execute();
                $product = $stmt->fetch();

                if ($product['stock_quantity'] < $qty) {
                    throw new Exception("Insufficient stock for Product ID: " . $pid);
                }

                $total += $product['price'] * $qty;
                $itemsToInsert[] = [
                    'product_id' => $pid,
                    'qty' => $qty,
                    'price' => $product['price']
                ];
            }

            // 3. Call Stored Procedure to Create Order Header
            // sp_create_order(customer_id, shipping_address_id, total, external_txn_id, payment_method, OUT new_id)

            $txnId = uniqid('txn_');
            $method = 'PayPal'; // Mock

            // Calling a procedure with OUT parameter in PDO is slightly complex involving loops.
            // To simplify for this context, we will replicate the PRODECURE logic here inside the transaction
            // OR use: SELECT @id; after call.

            $stmtOrder = $this->db->prepare("INSERT INTO orders (customer_id, shipping_address_id, total_amount, order_status, order_date) VALUES (?, ?, ?, 'Processing', NOW())");
            $stmtOrder->execute([$userId, $addressId, $total]);
            $orderId = $this->db->lastInsertId();

            // Payment
            $stmtPay = $this->db->prepare("INSERT INTO payments (order_id, external_transaction_id, method, transaction_status, payment_date) VALUES (?, ?, ?, 'Success', NOW())");
            $stmtPay->execute([$orderId, $txnId, $method]);

            // 4. Insert Items (Trigger `trg_update_stock_after_order` will handle stock reduction!)
            $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order) VALUES (?, ?, ?, ?)");

            foreach ($itemsToInsert as $item) {
                $stmtItem->execute([$orderId, $item['product_id'], $item['qty'], $item['price']]);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
