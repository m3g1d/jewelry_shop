<?php

require_once '../app/models/Product.php';
require_once '../app/models/Order.php';

class ShopController extends Controller
{

    public function index($categoryId = null)
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts($categoryId);
        $categories = $productModel->getCategories();

        $this->view('shop/index', ['products' => $products, 'categories' => $categories, 'active_category' => $categoryId]);
    }

    public function product($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if (!$product) {
            die("Product not found");
        }

        $this->view('shop/details', ['product' => $product]);
    }

    // Cart Methods
    public function cart()
    {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $productModel = new Product();

        $productsInCart = [];
        $total = 0;

        foreach ($cartItems as $pid => $qty) {
            $p = $productModel->getProductById($pid);
            if ($p) {
                $p['qty'] = $qty;
                $p['line_total'] = $p['price'] * $qty;
                $total += $p['line_total'];
                $productsInCart[] = $p;
            }
        }

        $this->view('shop/cart', ['cart' => $productsInCart, 'total' => $total]);
    }

    public function add_to_cart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $qty = (int) $_POST['quantity'];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $qty;
            } else {
                $_SESSION['cart'][$productId] = $qty;
            }

            $this->redirect('shop/cart');
        }
    }

    public function remove_from_cart($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        $this->redirect('shop/cart');
    }

    public function checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
            return;
        }

        if (empty($_SESSION['cart'])) {
            $this->redirect('shop');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address = $_POST['address'];
            $orderModel = new Order();

            // Calculate total again to be safe
            $cartItems = $_SESSION['cart'];
            // In a real app we pass items to logic to calc total and deduct stock
            // We'll trust Order Model to handle DB Transaction

            try {
                $orderId = $orderModel->createOrder($_SESSION['user_id'], $cartItems, $address);
                if ($orderId) {
                    unset($_SESSION['cart']);
                    $this->view('shop/success', ['order_id' => $orderId]);
                } else {
                    $error = "Failed to place order.";
                    $this->cart(); // Show cart with error (need to pass error)
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }

        } else {
            $this->view('shop/checkout');
        }
    }
}
