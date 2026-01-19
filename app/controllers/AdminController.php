<?php

require_once '../app/models/Product.php';
require_once '../app/models/Category.php';

class AdminController extends Controller
{

    public function __construct()
    {
        // Check if session role matches strict ENUM case (Admin, Warehouse)
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Warehouse')) {
            $this->redirect('auth/login');
            exit();
        }
    }

    public function dashboard()
    {
        $this->view('admin/dashboard');
    }

    // Product Management
    public function add_product()
    {
        $productModel = new Product();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock_quantity' => $_POST['stock'],
                'image_url' => $_POST['image_url'] ?? ''
            ];

            if ($productModel->create($data)) {
                $this->redirect('admin/products');
            }
        }

        $categories = $productModel->getCategories();
        $this->view('admin/add_product', ['categories' => $categories]);
    }

    public function products()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        $this->view('admin/products', ['products' => $products]);
    }

    public function edit_product($id)
    {
        $productModel = new Product();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock_quantity' => $_POST['stock'],
                'image_url' => $_POST['image_url'] ?? ''
            ];

            if ($productModel->update($id, $data)) {
                $this->redirect('admin/products');
            }
        }

        $product = $productModel->getProductById($id);
        $categories = $productModel->getCategories();
        $this->view('admin/edit_product', ['product' => $product, 'categories' => $categories]);
    }

    public function delete_product($id)
    {
        $productModel = new Product();
        $productModel->delete($id);
        $this->redirect('admin/products');
    }

    // Category Management
    public function categories()
    {
        $catModel = new Category();
        $categories = $catModel->getAll();
        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function add_category()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $catModel = new Category();
            $catModel->create($_POST['name']);
            $this->redirect('admin/categories');
        }
    }

    public function edit_category($id)
    {
        $catModel = new Category();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $catModel->update($id, $_POST['name']);
            $this->redirect('admin/categories');
        }
        $category = $catModel->getById($id);
        $this->view('admin/edit_category', ['category' => $category]);
    }

    public function delete_category($id)
    {
        $catModel = new Category();
        $catModel->delete($id);
        $this->redirect('admin/categories');
    }

    public function orders()
    {
        $db = Database::getInstance()->getConnection();
        // Join users to show who ordered
        $stmt = $db->query("SELECT o.*, u.first_name, u.last_name FROM orders o JOIN users u ON o.customer_id = u.user_id ORDER BY o.order_date DESC");
        $orders = $stmt->fetchAll();

        $this->view('admin/orders', ['orders' => $orders]);
    }

    public function update_order_status()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];

            // Simple interaction for prototype
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE orders SET order_status = :status WHERE order_id = :id");
            $stmt->execute([':status' => $status, ':id' => $orderId]);

            $this->redirect('admin/orders');
        }
    }
}
