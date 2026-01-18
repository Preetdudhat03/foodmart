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
// Clean up price, remove currency symbols if present for consistent storage/checking
$price = isset($data['price']) ? $data['price'] : '';
$image = isset($data['image']) ? $data['image'] : '';
$rating = isset($data['rating']) ? $data['rating'] : '';

// Check if already in favorites
$stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_title = ?");
$stmt->bind_param("is", $user_id, $title);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove if exists
    $deleteParams = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_title = ?");
    $deleteParams->bind_param("is", $user_id, $title);
    if ($deleteParams->execute()) {
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Removed from wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error removing item']);
    }
    $deleteParams->close();
} else {
    // Add if not exists
    $insertParams = $conn->prepare("INSERT INTO favorites (user_id, product_title, product_image, product_price, product_rating) VALUES (?, ?, ?, ?, ?)");
    $insertParams->bind_param("issss", $user_id, $title, $image, $price, $rating);
    if ($insertParams->execute()) {
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Added to wishlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding item']);
    }
    $insertParams->close();
}

$stmt->close();
$conn->close();
?>
