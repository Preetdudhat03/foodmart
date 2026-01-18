<?php include 'includes/header.php'; ?>
<?php include 'includes/db_connection.php'; ?>

<section class="py-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="bootstrap-tabs product-tabs">
          <div class="tabs-header d-flex justify-content-between border-bottom my-5">
            <h3>Search Results</h3>
          </div>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
              <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                <?php
                if (isset($_GET['q'])) {
                    $search = $_GET['q'];
                    $search = str_replace("%", "", $search); // Prevent some basic wildcard injection issues usage
                    $searchTerm = "%" . $conn->real_escape_string($search) . "%";
                    
                    $sql = "SELECT * FROM products WHERE title LIKE ? OR category LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $searchTerm, $searchTerm);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } elseif (isset($_GET['category'])) {
                    $category = $_GET['category'];
                    
                    if ($category == 'all') {
                         $sql = "SELECT * FROM products";
                         $stmt = $conn->prepare($sql);
                    } else {
                        $sql = "SELECT * FROM products WHERE category = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $category);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    echo "<h4>Category: " . htmlspecialchars($category) . "</h4>";
                }

                if (isset($result)) {
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="col">
                                <div class="product-item">
                                    <a href="#" class="btn-wishlist"><svg width="24" height="24"><use xlink:href="#heart"></use></svg></a>
                                    <figure>
                                        <a href="index.html" title="<?php echo htmlspecialchars($row['title']); ?>">
                                            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="tab-image">
                                        </a>
                                    </figure>
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <span class="qty"><?php echo htmlspecialchars($row['quantity_label']); ?></span>
                                    <span class="rating"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> <?php echo htmlspecialchars($row['rating']); ?></span>
                                    <span class="price"><?php echo htmlspecialchars($row['price']); ?></span>
                                    <div class="d-flex align-items-center justify-content-between">
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
                                        <a href="#" class="nav-link btn-add-to-cart">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No products found matching your criteria.</p>";
                    }
                    if(isset($stmt)) $stmt->close();
                } else {
                    echo "<p>Please enter a search term or select a category.</p>";
                }
                ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
