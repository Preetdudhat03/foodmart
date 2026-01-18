(function ($) {
  "use strict";

  // Check login status
  const isLoggedIn = $('body').hasClass('logged-in') || document.cookie.includes('PHPSESSID');

  // Helper to get wishlist from DB
  function fetchWishlist() {
    return $.ajax({
      url: 'actions/get_wishlist.php',
      method: 'GET',
      dataType: 'json',
      cache: false
    });
  }

  // Toggle wishlist item via DB
  function toggleWishlist(product, $btn) {
    $.ajax({
      url: 'actions/toggle_wishlist.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(product),
      success: function (response) {
        if (response.success) {
          // Update UI based on action
          if (response.action === 'added') {
            $btn.addClass('active').css('color', 'red');
          } else {
            $btn.removeClass('active').css('color', '');
            // If on favorite page, remove the item entirely
            if (window.location.pathname.includes('favorite.php')) {
              $btn.closest('.col').fadeOut(function () {
                $(this).remove();
                checkEmptyWishlist(); // Check if list is empty after removal
              });
            }
          }
        } else {
          console.error(response.message);
          if (response.message === 'User not logged in') {
            // Trigger login modal if user not logged in
            const loginModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal'));
            loginModal.show();
          }
        }
      },
      error: function (err) {
        console.error('Error toggling wishlist', err);
      }
    });
  }

  // Extract product data from DOM element
  function getProductData($productItem) {
    // Determine quantity if input exists, else 1
    let qty = 1;
    const $qtyInput = $productItem.find('input[name="quantity"]');
    if ($qtyInput.length > 0) {
      qty = parseInt($qtyInput.val()) || 1;
    }

    return {
      title: $productItem.find('h3').text().trim(),
      price: $productItem.find('.price').text().trim(),
      image: $productItem.find('figure img').attr('src'),
      qty: qty,
      rating: $productItem.find('.rating').text().trim()
    };
  }

  // Update heart icons on page load
  function updateWishlistUI() {
    fetchWishlist().then(function (wishlist) {
      // Create a set of titles for efficient lookup
      const titles = new Set(wishlist.map(function (p) { return p.title; }));

      $('.product-item').each(function () {
        const $item = $(this);
        const title = $item.find('h3').text().trim();
        const $btn = $item.find('.btn-wishlist');

        if (titles.has(title)) {
          $btn.addClass('active').css('color', 'red');
          $btn.find('use').attr('xlink:href', '#heart');
        } else {
          $btn.removeClass('active').css('color', '');
        }
      });
    }).fail(function (err) {
      // likely not logged in or array empty
    });
  }

  function checkEmptyWishlist() {
    const $container = $('#wishlist-container');
    if ($container.children(':visible').length === 0) {
      $container.html(`
            <div class="col-12">
              <div class="text-center py-5 rounded-4" style="background-color: #f8f9fa;">
                <svg width="80" height="80" class="text-muted mb-4">
                  <use xlink:href="#heart"></use>
                </svg>
                <h3 class="fw-bold">Your Wishlist is Empty</h3>
                <p class="text-muted mb-4">It seems you haven't added any products to your favorites yet.</p>
                <a href="index.php" class="btn btn-primary btn-lg rounded-3 px-5">Start Shopping</a>
              </div>
            </div>
          `);
    }
  }

  // Render wishlist items on favorite.php
  function renderWishlistPage() {
    const $container = $('#wishlist-container');
    if ($container.length === 0) return;

    fetchWishlist().then(function (wishlist) {
      $container.empty();

      if (wishlist.length === 0) {
        checkEmptyWishlist(); // Use helper to show empty state
        return;
      }

      wishlist.forEach(function (product) {
        const html = `
            <div class="col">
              <div class="product-item">
                <a href="#" class="btn-wishlist" style="color: red;">
                  <svg width="24" height="24"><use xlink:href="#heart"></use></svg>
                </a>
                <figure>
                  <a href="#" title="${product.title}">
                    <img src="${product.image}" class="tab-image">
                  </a>
                </figure>
                <h3>${product.title}</h3>
                <span class="qty">1 Unit</span>
                <span class="rating"><svg width="24" height="24" class="text-primary"><use xlink:href="#star-solid"></use></svg> ${product.rating || '4.5'}</span>
                <span class="price">${product.price}</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                      <span class="input-group-btn">
                          <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                            <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                          </button>
                      </span>
                      <input type="text" name="quantity" class="form-control input-number" value="1">
                      <span class="input-group-btn">
                          <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                              <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                          </button>
                      </span>
                  </div>
                  <button type="button" class="btn-add-to-cart border-0 bg-transparent p-2 text-decoration-none text-reset">Add to Cart <iconify-icon icon="uil:shopping-cart"></iconify-icon></button>
                </div>
              </div>
            </div>
          `;
        $container.append(html);
      });

      // Re-init simple interactivity for dynamic elements
      initQuantityLogic();

    }).fail(function (err) {
      // Not logged in or error
      $container.html(`
            <div class="col-12 text-center py-5">
                <h3 class="fw-bold mb-3">Please Login</h3>
                 <p class="text-muted mb-4">You need to be logged in to view your wishlist.</p>
                 <button class="btn btn-primary btn-lg rounded-3 px-5" data-bs-toggle="modal" data-bs-target="#loginModal">Login Now</button>
            </div>
         `);
    });
  }

  function initQuantityLogic() {
    // Event delegation for dynamically added elements
    $(document).off('click', '.quantity-right-plus').on('click', '.quantity-right-plus', function (e) {
      e.preventDefault();
      var $input = $(this).closest('.product-qty').find('.input-number');
      var quantity = parseInt($input.val());
      $input.val(quantity + 1);
    });

    $(document).off('click', '.quantity-left-minus').on('click', '.quantity-left-minus', function (e) {
      e.preventDefault();
      var $input = $(this).closest('.product-qty').find('.input-number');
      var quantity = parseInt($input.val());
      if (quantity > 1) { // Min 1
        $input.val(quantity - 1);
      }
    });
  }

  function attachEventListeners() {
    // Use vanilla JS event delegation with CAPTURE phase to ensure we catch it
    // even if Swiper or other scripts stop propagation.
    window.addEventListener('click', function (e) {
      // Handle Wishlist Button
      const btn = e.target.closest('.btn-wishlist');
      if (btn) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(btn);
        const $productItem = $btn.closest('.product-item');

        // Allow clicking on favorite page mainly to remove it, or on home to toggle.
        // On favorite page, removing it removes from DOM.
        const product = getProductData($productItem);

        toggleWishlist(product, $btn);
      }
    }, true); // <--- Capture phase
  }

  // Initialize
  $(document).ready(function () {
    attachEventListeners();
    updateWishlistUI();

    // Check if the wishlist container exists on the page to determine if we should render
    if ($('#wishlist-container').length > 0) {
      renderWishlistPage();
    }
  });

})(jQuery);
