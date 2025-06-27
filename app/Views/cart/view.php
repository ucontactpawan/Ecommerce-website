<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
</head>

<body style="background-color: #f1f3f6;">
    <?= view('templates/navbar') ?>

    <?php if (isset($cartItems) && !empty($cartItems)): ?>
        <div class="container my-4">
            <div class="bg-white p-4 rounded shadow-sm">
                <h4 class="mb-4 fw-bold">Shopping Cart</h4>

                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item d-flex align-items-start mb-4 border-bottom pb-3">
                        <!-- Product Image -->
                        <div class="me-3">
                            <img src="<?= base_url('images/' . esc($item['image'])) ?>" alt="Product" width="100" height="100">
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow-1">
                            <h5 class="mb-1"><?= esc($item['name']) ?></h5>
                            <p class="text-muted mb-1">Seller: YourStoreName</p>

                            <!-- Price and Total -->
                            <p class="mb-1 fw-bold text-danger item-price" data-price="<?= esc($item['price']) ?>">
                                ₹<?= number_format($item['price'], 2) ?>
                            </p>
                            <p class="mb-2 text-muted">
                                Total: ₹<span class="item-total"><?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </p>

                            <!-- Quantity & Actions -->
                            <div class="d-flex align-items-center">
                                <button class="btn btn-light border rounded-circle px-2 change-quantity" data-action="decrease" data-id="<?= esc($item['id']) ?>">-</button>
                                <input type="text" value="<?= esc($item['quantity']) ?>" readonly class="form-control text-center mx-2 quantity-input" style="width: 50px;">
                                <button class="btn btn-light border rounded-circle px-2 change-quantity" data-action="increase" data-id="<?= esc($item['id']) ?>">+</button>

                                <div class="ms-4">
                                    <a href="#" class="text-decoration-none me-3 text-primary save-for-later" data-id="<?= esc($item['id']) ?>">SAVE FOR LATER</a>
                                    <a href="#" class="text-decoration-none text-danger remove-item" data-id="<?= esc($item['id']) ?>">REMOVE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-end">
                    <button class="btn btn-warning fw-bold">PLACE ORDER</button>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info text-center py-5">
            <h4>Your cart is empty!</h4>
            <p><a href="<?= site_url('/') ?>" class="btn btn-primary mt-3">Continue Shopping</a></p>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const siteUrl = '<?= rtrim(base_url(), '/') ?>/index.php';
    </script>
    <script src="<?= base_url('js/cart.js') ?>"></script>
</body>

</html>