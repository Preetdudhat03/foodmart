<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = ['success' => false, 'message' => '', 'cart_count' => 0, 'cart_total' => 0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = floatval($_POST['price']);
            $image = $_POST['image'];
            $quantity = intval($_POST['quantity']);

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => $quantity
                ];
            }
            $response['success'] = true;
            $response['message'] = 'Item added to cart!';
            break;

        case 'update':
            $id = $_POST['id'];
            $quantity = intval($_POST['quantity']);
            if ($quantity > 0 && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
                $response['success'] = true;
                $response['message'] = 'Cart updated!';
            }
            break;

        case 'remove':
            $id = $_POST['id'];
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                $response['success'] = true;
                $response['message'] = 'Item removed!';
            }
            break;

        case 'clear':
            $_SESSION['cart'] = [];
            $response['success'] = true;
            $response['message'] = 'Cart cleared!';
            break;
    }

    // Calculate totals
    $count = 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
        $total += $item['price'] * $item['quantity'];
    }
    
    $response['cart_count'] = $count;
    $response['cart_total'] = $total;
    
    echo json_encode($response);
    exit;
}
?>
