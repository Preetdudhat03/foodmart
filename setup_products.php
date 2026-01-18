<?php
include 'includes/db_connection.php';

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    price VARCHAR(50),
    quantity_label VARCHAR(50),
    rating VARCHAR(10),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table products created successfully\n";
} else {
    die("Error creating table: " . $conn->error);
}

// Check if products exist
$check_sql = "SELECT count(*) as count FROM products";
$result = $conn->query($check_sql);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $products = [
        ['Bananas', 'images/thumb-bananas.png', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Crunchy Cookies', 'images/thumb-biscuits.png', '$18.00', '1 Unit', '4.5', 'Breads & Sweets'],
        ['Cucumber', 'images/thumb-cucumber.png', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Milk', 'images/thumb-milk.png', '$18.00', '1 Unit', '4.5', 'Dairy'],
        ['Avocado', 'images/thumb-avocado.png', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Raspberries', 'images/thumb-raspberries.png', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Orange Juice', 'images/thumb-orange-juice.png', '$18.00', '1 Unit', '4.5', 'Juices'],
        ['Tomato Ketchup', 'images/thumb-tomatoketchup.png', '$18.00', '1 Unit', '4.5', 'Groceries'],
        ['Apple', 'images/single-whole-red-apple-white.jpg', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Mango', 'images/mango-still-life.jpg', '$18.00', '1 Unit', '4.5', 'Fruits & Veges'],
        ['Sunstar Fresh Melon Juice', 'images/thumb-cucumber.png', '$18.00', '1 Unit', '4.5', 'Juices']
    ];

    $stmt = $conn->prepare("INSERT INTO products (title, image, price, quantity_label, rating, category) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($products as $product) {
        $stmt->bind_param("ssssss", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
        $stmt->execute();
    }
    echo "Products inserted successfully\n";
    $stmt->close();
} else {
    echo "Products already exist\n";
}

$conn->close();
?>
