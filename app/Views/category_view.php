<div class="main-container">
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="product-card mb-4">
                    <img src="<?= base_url('images/' . $product['image']) ?>" class="product-image" alt="<?= esc($product['name']) ?>">
                    <div class="product-name"><?= esc($product['name']) ?></div>
                    <div class="product-price">
                        ₹<?= number_format($product['price'], 0) ?>
                    </div>
                    <div class="card-body">
                        <div class="button-group">
                            <form method="POST" action="<?= site_url('cart/add') ?>" class="add-to-cart-form d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-primary add-to-cart-btn">
                                    <i class="bi bi-cart-plus"></i>
                                    <span class="btn-text">Add to Cart</span>
                                </button>
                            </form>
                            <button class="btn btn-sm btn-primary buy-now-btn">
                                <i class="bi bi-lightning-fill"></i>
                                <span class="btn-text">Buy Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>