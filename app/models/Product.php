<?php

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllProducts($categoryId = null)
    {
        $sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.category_id";
        if ($categoryId) {
            $sql .= " WHERE p.category_id = :cat_id";
        }
        $stmt = $this->db->prepare($sql);
        if ($categoryId) {
            $stmt->bindParam(':cat_id', $categoryId);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductById($id)
    {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getCategories()
    {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO products (category_id, name, description, price, stock_quantity, image_url) 
                VALUES (:category_id, :name, :description, :price, :stock_quantity, :image_url)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE products SET 
                category_id = :category_id, 
                name = :name, 
                description = :description, 
                price = :price, 
                stock_quantity = :stock_quantity, 
                image_url = :image_url 
                WHERE product_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        return $stmt->execute([$id]);
    }
}
