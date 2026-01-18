$(document).ready(function () {

    // Helper to parse price string "$18.00" -> 18.00
    function parsePrice(str) {
        return parseFloat(str.replace(/[^0-9.]/g, ''));
    }

    // Add to Cart Click Handler
    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $card = $btn.closest('.product-item'); // Adjust selector based on actual common container
        // If not .product-item, maybe .card (in shop.php)
        if ($card.length === 0) $card = $btn.closest('.card');

        var id = $card.data('id');
        var name = $card.find('h3').text() || $card.find('.card-title').text();
        var priceStr = $card.find('.price').text();
        var price = parsePrice(priceStr);
        var image = $card.find('img').attr('src');
        var quantity = parseInt($card.find('input[name="quantity"]').val()) || 1;

        // Fallback ID if data-id is missing (using name slug)
        if (!id) {
            id = name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        }

        // Animation feedback
        var originalIcon = $btn.html();
        $btn.html('<i class="bi bi-check-lg"></i>');

        $.ajax({
            url: 'actions/cart_actions.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'add',
                id: id,
                name: name,
                price: price,
                image: image,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    updateCartBadge(response.cart_count);
                    // Optional: Show toast
                    setTimeout(function () {
                        $btn.html(originalIcon);
                    }, 1500);
                }
            }
        });
    });

    // Update Cart Badge
    function updateCartBadge(count) {
        // Offcanvas badge
        $('#offcanvasCart .badge').text(count);
        // Header badge (if any, usually same as offcanvas trigger)
        // You might want to add a class to the header badge to target it easily
    }

    // Update Quantity in Cart Page (Delegate moved here if we want centralized logic, but I put it in cart.php script block for simplicity. 
    // Ideally it should be here)

});
