<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid justify-content-between align-items-center">

        <!-- Mobile toggle button -->
        <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- Brand/logo -->
        <a class="navbar-brand fw-bold" href="#">MyShop</a>

        <!-- Desktop navigation links -->
        <ul class="navbar-nav flex-row gap-3 d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Men</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Women</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Kids</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Beauty</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">GenZ</a></li>
        </ul>

        <!-- Search bar -->
        <form class="search-box d-none d-lg-block mx-4 w-50">
            <div class="input-group rounded-pill bg-light">
                <span class="input-group-text bg-transparent border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="search" class="form-control border-0 bg-transparent" placeholder="Search for products">
            </div>
        </form>

        <!-- Icons (Profile, Wishlist, Cart) -->
        <ul class="navbar-nav flex-row gap-3 text-center">
            <li class="nav-item">
                <a class="nav-link d-flex flex-column align-items-center" href="#">
                    <i class="bi bi-person fs-5"></i>
                    <span class="small">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex flex-column align-items-center" href="#">
                    <i class="bi bi-heart fs-5"></i>
                    <span class="small">Wishlist</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex flex-column align-items-center position-relative" href="<?= site_url('cart') ?>">
                    <div class="position-relative">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75em; display: none;"></span>
                    </div>
                    <span class="small">Cart</span>
                </a>
            </li>

            <!-- show Logout if user is logged in -->
            <?php if (session()->has('id')): ?>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="<?= site_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                        <span class="small">Logout</span>
                    </a>
                </li>

            <?php else: ?>
                <!-- Show Login if user is not logged in -->
                <li>
                    <a class="nav-link d-flex flex-column align-items-center" href="<?= site_url('auth/login') ?>">
                        <i class="bi bi-person fs-5"></i>
                        <span class="small">Login</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Mobile navigation -->
    <div class="collapse d-lg-none bg-white px-3 pt-2" id="mobileNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Men</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Women</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Kids</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Beauty</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">GenZ</a></li>
        </ul>
    </div>
</nav>

<script>
    // Initialize cart count on page load only if cart.js is not loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Give cart.js time to load and initialize
        setTimeout(() => {
            // Only run if updateCartCount function doesn't exist (cart.js not loaded)
            if (typeof updateCartCount !== 'function') {
                console.log('cart.js not loaded, using navbar fallback');
                fetch('<?= site_url('cart/count') ?>')
                    .then(response => response.json())
                    .then(data => {
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement && data.count > 0) {
                            cartCountElement.textContent = data.count;
                            cartCountElement.style.display = 'inline-block';
                        }
                    })
                    .catch(error => console.log('Cart count fetch failed:', error));
            } else {
                console.log('cart.js loaded, skipping navbar cart count initialization');
            }
        }, 100);
    });
</script>