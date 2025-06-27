    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Define JavaScript variables -->
    <script>
        const siteUrl = '<?= rtrim(site_url(), '/') ?>';
        const baseUrl = '<?= rtrim(base_url(), '/') ?>';
    </script>

    <!-- Cart functionality -->
    <script src="<?= base_url('js/cart-simple.js') ?>"></script>

    <!-- Electronics slider (only on home page) -->
    <?php
    $uri = service('uri');
    if ($uri->getSegment(1) === '' || $uri->getSegment(1) === null) : // Home page
    ?>
        <script src="<?= base_url('js/electronics-slider.js') ?>"></script>
    <?php endif; ?>

    </div> <!-- Close container div -->
    </body>

    </html>