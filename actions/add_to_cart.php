<?php
session_start();
header('Content-Type: application/json');
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title'])) {
    echo json_encode(['success' => false, 'message' => 'Product data missing']);
    exit;
}

$title = $data['title'];
$price = isset($data['price']) ? $data['price'] : '';
$image = isset($data['image']) ? $data['image'] : '';
$qty = isset($data['qty']) ? intval($data['qty']) : 1;

// Check if item already exists in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_title = ?");
$stmt->bind_param("is", $user_id, $title);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Update quantity
    $new_qty = $row['quantity'] + $qty;
    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $new_qty, $row['id']);
    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cart updated', 'new_qty' => $new_qty]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating cart']);
    }
    $updateStmt->close();
} else {
    // Insert new item
    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_title, product_image, product_price, quantity) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->bind_param("isssi", $user_id, $title, $image, $price, $qty);
    if ($insertStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
    }
    $insertStmt->close();
}

$stmt->close();
$conn->close();
?>
