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
    echo json_encode(['success' => false, 'message' => 'Product title missing']);
    exit;
}

$title = $data['title'];

$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_title = ?");
$stmt->bind_param("is", $user_id, $title);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing item']);
}

$stmt->close();
$conn->close();
?>
