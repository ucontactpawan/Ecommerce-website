<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop-ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
    <script>
        window.siteUrl = '<?= rtrim(site_url(), '/') ?>';
    </script>
</head>

<body>
    <!--     
  include navbar  -->

    <?= view('templates/navbar') ?>
    <!-- Main content area -->
    <div class="container">
        <!-- banner Section -->
        <div class="main-container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <img src="<?= base_url('images/banner.jpg') ?>" alt="Smart shopping - Trustesd by millions" class="img-fluid mb-4">
                </div>
            </div>
        </div>

        <!-- Product grid-->
        <div class="main-container">
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?= base_url('images/' . $product['image']) ?>" class="card-img-top product-img" alt="<?= $product['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                <p class="card-text">₹<?= number_format($product['price'], 2) ?></p>
                                <form method="POST" action="<?= site_url('cart/add') ?>" class="add-to-cart-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" class="btn btn-primary add-to-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div> <?php endforeach; ?>
            </div>
        </div>


        <!-- Best of Electronics section -->
        <section class="electronics-section mt-3">
            <div class="container">
                <div class="main-container">
                    <h2 class="section-title mb-3">Best of Electronics</h2>
                    <div class="electronics-slider">
                        <div class="electronics-track">
                            <?php foreach ($electronics as $electronic): ?>
                                <div class="electronics-item">
                                    <a href="<?= base_url('category/' . strtolower($electronic['name']))  ?>" class="electronics-card">
                                        <div class="electronics-image">
                                            <img src="<?= base_url('images/' . $electronic['image']) ?>" alt="<?= esc($electronic['name']) ?>">
                                        </div>
                                        <div class="electronics-info">
                                            <h3 class="electronics-title"><?= esc($electronic['name']) ?></h3>
                                            <p class="electronics-price">From ₹<?= number_format($electronic['price'], 2) ?></p>
                                            <?php if (isset($electronic['tag'])): ?>
                                                <div class="electronics-tag"><?= esc($electronic['tag']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="slider-nav prev" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="slider-nav next" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Define the site URL and base URL for use in cart.js
            const siteUrl = '<?= rtrim(site_url(), '/') ?>';
            const baseUrl = '<?= rtrim(base_url(), '/') ?>';
        </script>

        <script src="<?= base_url('js/cart-simple.js') ?>"></script>
        <script src="<?= base_url('js/electronics-slider.js') ?>"></script>

</body>

</html>