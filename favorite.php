<?php include 'includes/header.php'; ?>

<section class="py-5 mb-5" style="background-image: url('images/background-pattern.jpg'); background-repeat: no-repeat; background-size: cover;">
  <div class="container text-center py-5">
    <h1 class="fw-bold">My Wishlist</h1>
    <p class="lead text-muted mb-0">Your favorite products in one place</p>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="col-12 text-center py-5">
            <h3 class="fw-bold mb-3">Please Login</h3>
            <p class="text-muted mb-4">You need to be logged in to view your wishlist.</p>
            <button class="btn btn-primary btn-lg rounded-3 px-5" data-bs-toggle="modal" data-bs-target="#loginModal">Login Now</button>
        </div>
    <?php else: ?>
        <div id="wishlist-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 js-wishlist-container">
            <!-- Content will be injected by js/wishlist.js -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>