<?php
require_once __DIR__ . '/../../Core/Model.php';

class Product extends Model {

    // This method fetches all products from the database
    public function getAllProducts() {
        $sql = "SELECT * FROM products"; // Make sure the table name is correct
        $stmt = $this->query($sql); // Execute the query
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the result as an associative array
    }
}
