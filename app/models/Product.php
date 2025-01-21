<?php
class Product
{
    public static function getAllProducts()
    {
        $db = Db::getConnection();
        $stmt = $db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProductById($id)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createProduct($name, $price, $description)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $description]);
        return self::getProductById($db->lastInsertId());
    }

    public static function updateProduct($id, $name, $price, $description)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $id]);
        return self::getProductById($id);
    }

    public static function deleteProduct($id)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public static function searchProducts($name)
    {
        $db = Db::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ?");
        $stmt->execute(["%$name%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
