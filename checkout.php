<?php
include 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='shop.php';</script>";
    exit;
}

// Fetch user address
include 'includes/db_connection.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email, phone, address, city, zip_code, country FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$has_address = !empty($user['address']) && !empty($user['city']) && !empty($user['zip_code']);
?>

<!-- Hero Section -->
<section class="py-5 mb-5" style="background: url('images/background-pattern.jpg'); background-size: cover; background-repeat: no-repeat;">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-dark fw-bold display-5">Checkout</h1>
      <nav class="breadcrumb">
        <a class="breadcrumb-item nav-link" href="index.php">Home</a>
        <a class="breadcrumb-item nav-link" href="cart.php">Cart</a>
        <span class="breadcrumb-item active" aria-current="page">Checkout</span>
      </nav>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-7">
        <h4 class="mb-4">Billing Details</h4>
        <form id="checkoutForm" action="actions/place_order.php" method="POST">
          <div class="row g-3">
             <div class="col-12">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
             <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required <?php echo $has_address ? '' : 'autofocus'; ?>>
            </div>
            <div class="col-md-5">
              <label class="form-label">City</label>
              <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Zip Code</label>
              <input type="text" class="form-control" name="zip_code" value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>" required>
            </div>
             <div class="col-md-4">
              <label class="form-label">Country</label>
              <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>" required>
            </div>
          </div>
          
          <hr class="my-4">
          
          <h4 class="mb-3">Payment Method</h4>
          <div class="my-3">
            <div class="form-check">
              <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
              <label class="form-check-label" for="credit">Credit card</label>
            </div>
            <div class="form-check">
              <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
              <label class="form-check-label" for="debit">Debit card</label>
            </div>
            <div class="form-check">
              <input id="cod" name="paymentMethod" type="radio" class="form-check-input" required>
              <label class="form-check-label" for="cod">Cash on Delivery</label>
            </div>
          </div>
          
          <!-- Mock Payment Fields -->
          <div class="row gy-3" id="card-fields">
            <div class="col-md-6">
              <label class="form-label">Name on card</label>
              <input type="text" class="form-control" placeholder="John Doe">
              <small class="text-muted">Full name as displayed on card</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Credit card number</label>
              <input type="text" class="form-control" placeholder="0000 0000 0000 0000">
            </div>
             <div class="col-md-3">
              <label class="form-label">Expiration</label>
              <input type="text" class="form-control" placeholder="MM/YY">
            </div>
            <div class="col-md-3">
              <label class="form-label">CVV</label>
              <input type="text" class="form-control" placeholder="123">
            </div>
          </div>

        </form>
      </div>
      
      <div class="col-lg-5">
        <div class="card border-0 rounded-4 shadow-sm">
          <div class="card-body p-4">
            <h4 class="d-flex justify-content-between align-items-center mb-4">
              <span class="text-primary">Your cart</span>
              <span class="badge bg-primary rounded-pill"><?php echo count($_SESSION['cart']); ?></span>
            </h4>
            <ul class="list-group mb-3">
              <?php 
                $total = 0;
                foreach($_SESSION['cart'] as $item): 
                  $line_total = $item['price'] * $item['quantity'];
                  $total += $line_total;
              ?>
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                  <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                </div>
                <span class="text-muted">$<?php echo number_format($line_total, 2); ?></span>
              </li>
              <?php endforeach; ?>
              <li class="list-group-item d-flex justify-content-between bg-light">
                <div class="text-success">
                  <h6 class="my-0">Promo code</h6>
                  <small>EXAMPLECODE</small>
                </div>
                <span class="text-success">−$0.00</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Total (USD)</span>
                <strong>$<?php echo number_format($total, 2); ?></strong>
              </li>
            </ul>

            <button class="w-100 btn btn-primary btn-lg rounded-3" type="button" data-bs-toggle="modal" data-bs-target="#confirmOrderModal">Place Order</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Order Confirmation Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title">Confirm Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <h3 class="mb-3">Total Amount: $<?php echo number_format($total, 2); ?></h3>
        <p class="text-muted mb-4">Are you sure you want to place this order?</p>
        <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary px-4" id="confirmOrderBtn">Confirm Payment & Order</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Toggle card fields visibility based on radio
    $('input[name="paymentMethod"]').change(function() {
        if ($('#cod').is(':checked')) {
            $('#card-fields').slideUp();
        } else {
            $('#card-fields').slideDown();
        }
    });

    $('#confirmOrderBtn').click(function() {
        var form = $('#checkoutForm')[0];
        if (form.checkValidity()) {
            $(this).prop('disabled', true).text('Processing...');
            form.submit();
        } else {
            $('#confirmOrderModal').modal('hide');
            form.reportValidity();
            $(this).prop('disabled', false).text('Confirm Payment & Order');
        }
    });
});
</script>
