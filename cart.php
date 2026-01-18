<?php
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="py-5 mb-5" style="background: url('images/background-pattern.jpg'); background-size: cover; background-repeat: no-repeat;">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-dark fw-bold display-5">Cart</h1>
      <nav class="breadcrumb">
        <a class="breadcrumb-item nav-link" href="index.php">Home</a>
        <span class="breadcrumb-item active" aria-current="page">Cart</span>
      </nav>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-9">
        <div class="table-responsive">
          <table class="table table-borderless align-middle cart-table">
            <thead class="bg-light">
              <tr>
                <th scope="col" class="py-3 ps-4">Product</th>
                <th scope="col" class="py-3">Price</th>
                <th scope="col" class="py-3">Quantity</th>
                <th scope="col" class="py-3">Total</th>
                <th scope="col" class="py-3"></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($_SESSION['cart'])): ?>
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <h4 class="text-muted">Your cart is currently empty.</h4>
                    <a href="shop.php" class="btn btn-primary rounded-3 mt-3">Return to Shop</a>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                  <tr class="border-bottom">
                    <td class="ps-4">
                      <div class="d-flex align-items-center">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid rounded-3 me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div>
                          <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                        </div>
                      </div>
                    </td>
                    <td><h5 class="mb-0">$<?php echo number_format($item['price'], 2); ?></h5></td>
                    <td>
                      <div class="input-group" style="width: 120px;">
                        <button class="btn btn-light border btn-sm update-qty" data-action="minus" data-id="<?php echo $item['id']; ?>">-</button>
                        <input type="text" class="form-control form-control-sm text-center border-0 bg-light" value="<?php echo $item['quantity']; ?>" readonly>
                        <button class="btn btn-light border btn-sm update-qty" data-action="plus" data-id="<?php echo $item['id']; ?>">+</button>
                      </div>
                    </td>
                    <td><h5 class="mb-0 fw-bold text-primary">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></h5></td>
                    <td>
                      <button class="btn btn-link text-danger p-0 remove-item" data-id="<?php echo $item['id']; ?>">
                        <i class="bi bi-trash fs-5"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Cart Totals</h4>
            <div class="d-flex justify-content-between mb-3">
              <span class="text-muted">Subtotal</span>
              <?php 
                $total = 0;
                foreach($_SESSION['cart'] ?? [] as $item) $total += $item['price'] * $item['quantity'];
              ?>
              <span class="fw-bold">$<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
              <span class="text-muted">Shipping</span>
              <span class="fw-bold text-success">Free</span>
            </div>
            <div class="d-flex justify-content-between mb-4">
              <span class="fw-bold fs-5">Total</span>
              <span class="fw-bold fs-5 text-primary">$<?php echo number_format($total, 2); ?></span>
            </div>
            <a href="checkout.php" class="btn btn-primary w-100 btn-lg rounded-3 <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>">Proceed to Checkout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
  $(document).ready(function() {
    // Current formatting logic being handled by backend reload mostly, but good to have responsiveness
    $('.update-qty').click(function() {
      let id = $(this).data('id');
      let currentQty = parseInt($(this).siblings('input').val());
      let action = $(this).data('action');
      let newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;

      if (newQty < 1) return;

      $.post('actions/cart_actions.php', {
        action: 'update',
        id: id,
        quantity: newQty
      }, function(response) {
        location.reload(); 
      }, 'json');
    });

    $('.remove-item').click(function() {
      if(!confirm('Are you sure you want to remove this item?')) return;
      let id = $(this).data('id');
      $.post('actions/cart_actions.php', { action: 'remove', id: id }, function(response) {
        location.reload();
      }, 'json');
    });
  });
</script>
