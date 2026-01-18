<?php
include 'includes/header.php';
include 'includes/db_connection.php';

// Pagination setup
$limit = 12; // Products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter setup
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Build Query for Products
$sql = "SELECT * FROM products";
$count_sql = "SELECT COUNT(*) as total FROM products";
$params = [];
$types = "";

if ($category_filter != 'all') {
    $sql .= " WHERE category = ?";
    $count_sql .= " WHERE category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Prepare and Execute Product Query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Prepare and Execute Count Query
$count_stmt = $conn->prepare($count_sql);
if ($category_filter != 'all') {
    $count_stmt->bind_param("s", $category_filter);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_products = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $limit);

// Fetch Categories for Sidebar
$cat_sql = "SELECT DISTINCT category FROM products ORDER BY category";
$cat_result = $conn->query($cat_sql);
?>

<section class="py-5 mb-5" style="background: url('images/background-pattern.jpg'); background-size: cover; background-repeat: no-repeat;">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-dark fw-bold display-5">Shop</h1>
      <nav class="breadcrumb">
        <a class="breadcrumb-item nav-link" href="index.php">Home</a>
        <span class="breadcrumb-item active" aria-current="page">Shop</span>
      </nav>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Filters -->
      <aside class="col-md-3">
        <div class="sidebar">
          <div class="widget-menu mb-5">
             <div class="widget-search-bar mb-4">
              <form role="search" method="get" action="shop.php" class="d-flex position-relative">
                <input class="form-control form-control-lg rounded-3 ps-5" type="text" name="category" placeholder="Search Category..." aria-label="Search">
                 <div class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                     <svg width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#search"></use></svg>
                 </div>
              </form>
            </div>
          
            <h3 class="widget-title text-uppercase fw-bold pb-3 mb-3 border-bottom">Categories</h3>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="shop.php" class="d-flex justify-content-between text-decoration-none text-dark <?php echo $category_filter == 'all' ? 'fw-bold text-primary' : ''; ?>">
                  <span>All</span>
                </a>
              </li>
              <?php while($cat = $cat_result->fetch_assoc()): ?>
              <li class="mb-2">
                <a href="shop.php?category=<?php echo urlencode($cat['category']); ?>" class="d-flex justify-content-between text-decoration-none text-dark <?php echo $category_filter == $cat['category'] ? 'fw-bold text-primary' : ''; ?>">
                  <span><?php echo htmlspecialchars($cat['category']); ?></span>
                </a>
              </li>
              <?php endwhile; ?>
            </ul>
          </div>
          
          <!-- Filter by Price (Visual only for now) -->
          <div class="widget-price mb-5">
             <h3 class="widget-title text-uppercase fw-bold pb-3 mb-3 border-bottom">Filter by Price</h3>
             <div class="filter-price">
                 <div class="d-flex align-items-center gap-2 mb-2">
                     <input type="number" class="form-control" placeholder="Min" min="0">
                     <span>-</span>
                     <input type="number" class="form-control" placeholder="Max" min="0">
                 </div>
                 <button class="btn btn-dark w-100 rounded-1">Filter</button>
             </div>
          </div>
        </div>
      </aside>

      <!-- IDs for Product Grid -->
      <main class="col-md-9">
        <div class="filter-shop d-flex justify-content-between align-items-center mb-5">
          <div class="showing-product">
            <p class="m-0">Showing <?php echo $offset + 1; ?>–<?php echo min($offset + $limit, $total_products); ?> of <?php echo $total_products; ?> results</p>
          </div>
          <div class="sort-by">
            <select class="form-select form-select-sm border-0 bg-light p-2 rounded-3" aria-label="Default select example">
              <option selected>Default sorting</option>
              <option value="1">Sort by popularity</option>
              <option value="2">Sort by average rating</option>
              <option value="3">Sort by newness</option>
              <option value="4">Sort by price: low to high</option>
              <option value="5">Sort by price: high to low</option>
            </select>
          </div>
        </div>

        <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col">
              <div class="product-item">
                <a href="#" class="btn-wishlist"><svg width="24" height="24"><use xlink:href="#heart"></use></svg></a>
                <figure>
                  <a href="index.html" title="<?php echo htmlspecialchars($row['title']); ?>">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="tab-image w-100" style="object-fit: contain; height: 200px;">
                  </a>
                </figure>
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <span class="qty"><?php echo htmlspecialchars($row['quantity_label']); ?></span>
                <span class="rating"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> <?php echo htmlspecialchars($row['rating']); ?></span>
                <span class="price"><?php echo htmlspecialchars($row['price']); ?></span>
                <div class="d-flex align-items-center justify-content-between mt-3">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link btn-add-to-cart"><iconify-icon icon="uil:shopping-cart" class="fs-4"></iconify-icon></a>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
               <div class="alert alert-info py-4 text-center">No products found in this category.</div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav class="navigation paging-navigation text-center mt-5" role="navigation">
          <div class="pagination loop-pagination d-flex justify-content-center align-items-center">
            <?php if ($page > 1): ?>
              <a href="shop.php?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_filter); ?>" class="pagination-arrow d-flex align-items-center mx-1">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
              </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <a href="shop.php?page=<?php echo $i; ?>&category=<?php echo urlencode($category_filter); ?>" class="page-numbers btn btn-outline-dark mx-1 <?php echo $i == $page ? 'active bg-primary border-primary text-white' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
              <a href="shop.php?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_filter); ?>" class="pagination-arrow d-flex align-items-center mx-1">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
              </a>
            <?php endif; ?>
          </div>
        </nav>
        <?php endif; ?>

      </main>
    </div>
  </div>
</section>

<?php 
$stmt->close();
$count_stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>
