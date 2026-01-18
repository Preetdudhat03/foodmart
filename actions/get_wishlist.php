<?php
session_start();
header('Content-Type: application/json');
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]); // Return empty array if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT product_title as title, product_image as image, product_price as price, product_rating as rating, 1 as qty FROM favorites WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$favorites = [];
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}

echo json_encode($favorites);

$stmt->close();
$conn->close();
?>
