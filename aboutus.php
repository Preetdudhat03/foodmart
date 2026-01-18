<?php
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="py-5 mb-5" style="background: url('images/background-pattern.jpg'); background-size: cover; background-repeat: no-repeat;">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-dark fw-bold display-5">About Us</h1>
      <nav class="breadcrumb">
        <a class="breadcrumb-item nav-link" href="index.php">Home</a>
        <span class="breadcrumb-item active" aria-current="page">About Us</span>
      </nav>
    </div>
  </div>
</section>

<!-- Our Story Section -->
<section class="py-5">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <img src="images/post-thumb-1.jpg" alt="Our Story" class="img-fluid rounded-4 shadow">
      </div>
      <div class="col-lg-6 ps-lg-5">
        <h2 class="display-6 fw-bold mb-4">We Are Best in Organic Food</h2>
        <p class="lead text-muted mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        <p class="mb-4">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        
        <div class="row g-4 mb-4">
          <div class="col-sm-6">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                <i class="bi bi-star-fill fs-4"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">Top Quality</h5>
                <small class="text-muted">Best products only</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex align-items-center">
              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                <i class="bi bi-truck fs-4"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">Fast Delivery</h5>
                <small class="text-muted">Doorstep service</small>
              </div>
            </div>
          </div>
        </div>

        <a href="shop.php" class="btn btn-dark btn-lg rounded-3 px-5">Shop Now</a>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
  <div class="container-fluid">
    <div class="row text-center g-4">
      <div class="col-md-3 col-6">
        <div class="p-4 bg-white rounded-4 shadow-sm h-100">
          <h2 class="display-4 fw-bold text-primary mb-0">20k+</h2>
          <p class="text-muted mb-0">Happy Customers</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 bg-white rounded-4 shadow-sm h-100">
          <h2 class="display-4 fw-bold text-primary mb-0">500+</h2>
          <p class="text-muted mb-0">Products</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 bg-white rounded-4 shadow-sm h-100">
          <h2 class="display-4 fw-bold text-primary mb-0">50+</h2>
          <p class="text-muted mb-0">Team Members</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 bg-white rounded-4 shadow-sm h-100">
          <h2 class="display-4 fw-bold text-primary mb-0">15+</h2>
          <p class="text-muted mb-0">Awards Won</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="py-5 my-5">
  <div class="container-fluid">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold">Why Choose Us</h2>
      <p class="text-muted w-75 mx-auto">We provide the best organic food with high quality standards. Our mission is to make healthy food accessible to everyone.</p>
    </div>
    
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
          <div class="mb-3 text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93c0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41c0 2.08-.8 3.97-2.1 5.39z"/></svg>
          </div>
          <h4 class="fw-bold">Global Shipping</h4>
          <p class="text-muted mb-0">We ship our products worldwide with secure packaging to ensure freshness.</p>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
          <div class="mb-3 text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
          </div>
          <h4 class="fw-bold">Quality Guaranteed</h4>
          <p class="text-muted mb-0">Every product is quality checked before shipping. We ensure 100% organic certification.</p>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
          <div class="mb-3 text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="currentColor" d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15c0-1.09 1.01-1.85 2.7-1.85c1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61c0 2.31 1.91 3.46 4.7 4.13c2.5.6 3 1.48 3 2.41c0 .69-.49 1.79-2.7 1.79c-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55c0-2.84-2.43-3.81-4.7-4.4z"/></svg>
          </div>
          <h4 class="fw-bold">Best Prices</h4>
          <p class="text-muted mb-0">We offer competitive prices for premium organic products without compromising quality.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<!--
<section class="py-5">
  <div class="container-fluid">
    <h2 class="display-5 fw-bold text-center mb-5">Our Team</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
       ... Team members can go here ...
    </div>
  </div>
</section>
-->

<?php include 'includes/footer.php'; ?>
