<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid Request";
    exit;
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch Order Info (Verify it belongs to user)
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch Order Items
$stmt_items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Order #<?php echo $order['id']; ?></h5>
        <p class="text-muted mb-0">Placed on <?php echo date("F d, Y h:i A", strtotime($order['created_at'])); ?></p>
    </div>
    <span class="badge bg-<?php echo ($order['order_status'] == 'Completed' ? 'success' : ($order['order_status'] == 'Cancelled' ? 'danger' : 'warning')); ?> fs-6">
        <?php echo $order['order_status']; ?>
    </span>
</div>

<div class="card bg-light border-0 mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-2">Shipping Address</h6>
        <p class="mb-0 text-muted"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
        <p class="mb-0 mt-2 text-muted"><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
    </div>
</div>

<h6 class="fw-bold mb-3">Items</h6>
<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="text-muted border-bottom">
            <tr>
                <th scope="col">Product</th>
                <th scope="col" class="text-center">Qty</th>
                <th scope="col" class="text-end">Price</th>
                <th scope="col" class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while($item = $items_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td class="text-center"><?php echo $item['quantity']; ?></td>
                <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="border-top">
                <td colspan="3" class="text-end pt-3"><strong>Subtotal</strong></td>
                <td class="text-end pt-3">$<?php echo number_format($order['total_amount'], 2); ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                <td class="text-end">Free</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end pt-3"><h5 class="fw-bold text-primary">Total</h5></td>
                <td class="text-end pt-3"><h5 class="fw-bold text-primary">$<?php echo number_format($order['total_amount'], 2); ?></h5></td>
            </tr>
        </tbody>
    </table>
</div>
