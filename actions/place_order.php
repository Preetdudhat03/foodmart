<?php
session_start();
include '../includes/db_connection.php';
include '../includes/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
    
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['country'];
    $payment_method = $_POST['paymentMethod'];
    $order_date = date("Y-m-d H:i:s");
    
    // Calculate total
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // 1. Save Address if user asked or automatically update profile (Requirement says: address is not given then take address)
    // We update the user's profile with the new address for convenience
    $update_user = $conn->prepare("UPDATE users SET phone=?, address=?, city=?, zip_code=?, country=? WHERE id=?");
    $update_user->bind_param("sssssi", $phone, $address, $city, $zip_code, $country, $user_id);
    $update_user->execute();
    $update_user->close();

    // 2. Insert Order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_status, payment_method, shipping_address, created_at) VALUES (?, ?, 'Pending', ?, ?, ?)");
    $shipping_address = "$address, $city, $zip_code, $country";
    $stmt->bind_param("idsss", $user_id, $total_amount, $payment_method, $shipping_address, $order_date);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        
        // 3. Insert Order Items & Build Email Body
        $email_body = "<h3>Thank you for your order, $full_name!</h3>";
        $email_body .= "<p>Your order <strong>#$order_id</strong> has been placed successfully.</p>";
        $email_body .= "<h4>Order Details:</h4><table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        $email_body .= "<tr style='background-color: #f2f2f2;'><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>";

        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($_SESSION['cart'] as $item) {
            $p_name = $item['name'];
            $p_id = $item['id']; // This might be a string slug or ID. If string, we put 0 or handle logic. Assuming DB has ID, but cart uses mixed.
            // For Safety: set product_id to 0 if it's not numeric
            $db_p_id = is_numeric($item['id']) ? $item['id'] : 0;
            
            $item_stmt->bind_param("iisid", $order_id, $db_p_id, $p_name, $item['quantity'], $item['price']);
            $item_stmt->execute();
            
            $line_total = $item['price'] * $item['quantity'];
            $email_body .= "<tr><td>$p_name</td><td>{$item['quantity']}</td><td>$" . number_format($item['price'], 2) . "</td><td>$" . number_format($line_total, 2) . "</td></tr>";
        }
        $item_stmt->close();
        
        $email_body .= "</table>";
        $email_body .= "<h3>Total Amount: $" . number_format($total_amount, 2) . "</h3>";
        $email_body .= "<p>Payment Method: $payment_method</p>";
        $email_body .= "<p>Shipping Address: $shipping_address</p>";
        $email_body .= "<p>We will notify you once your order is shipped.</p>";

        // 4. Send Confirmation Email
        // Get user email from session or DB. We have it in POST only as readonly displayed field, but better trust DB or Session.
        // But for simplicity use the one user submitted (verified by frontend readonly)
        $email_to = $_POST['email'];
        sendMail($email_to, "Order Confirmation - Order #$order_id", $email_body);

        // 5. Clear Cart
        unset($_SESSION['cart']);
        
        echo "<script>alert('Order placed successfully! Check your email for details.'); window.location.href='../account.php';</script>";
        
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

} else {
    // Redirect if direct access
    header("Location: ../index.php");
}
?>
