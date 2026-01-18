<?php
include 'includes/header.php';
include 'includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch Orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!-- Hero Section -->
<section class="py-5 mb-5" style="background: url('images/background-pattern.jpg'); background-size: cover; background-repeat: no-repeat;">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-dark fw-bold display-5">My Orders</h1>
      <nav class="breadcrumb">
        <a class="breadcrumb-item nav-link" href="index.php">Home</a>
        <a class="breadcrumb-item nav-link" href="account.php">Account</a>
        <span class="breadcrumb-item active" aria-current="page">My Orders</span>
      </nav>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <h4 class="mb-4">Order History</h4>
            
            <?php if ($orders_result->num_rows > 0): ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="bg-light">
                    <tr>
                      <th scope="col">Order #</th>
                      <th scope="col">Date</th>
                      <th scope="col">Status</th>
                      <th scope="col">Total</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                      <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo date("M d, Y", strtotime($order['created_at'])); ?></td>
                        <td>
                          <?php 
                            $status_color = 'warning';
                            if($order['order_status'] == 'Completed') $status_color = 'success';
                            if($order['order_status'] == 'Cancelled') $status_color = 'danger';
                          ?>
                          <span class="badge bg-<?php echo $status_color; ?>"><?php echo $order['order_status']; ?></span>
                        </td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary rounded-3 view-order-btn" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#orderModal" 
                                  data-id="<?php echo $order['id']; ?>">
                            View Details
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="text-center py-5">
                <div class="mb-3">
                  <i class="bi bi-bag-x display-1 text-muted"></i>
                </div>
                <h5>No orders found</h5>
                <p class="text-muted">Looks like you haven't placed any orders yet.</p>
                <a href="shop.php" class="btn btn-primary rounded-3">Start Shopping</a>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="order-details-content">
        <div class="text-center py-5">
           <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $('.view-order-btn').click(function() {
        var orderId = $(this).data('id');
        $('#order-details-content').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
        
        // Fetch order details via AJAX
        $.ajax({
            url: 'actions/get_order_details.php',
            type: 'GET',
            data: { id: orderId },
            success: function(response) {
                $('#order-details-content').html(response);
            },
            error: function() {
                $('#order-details-content').html('<div class="alert alert-danger">Failed to load order details.</div>');
            }
        });
    });
});
</script>
