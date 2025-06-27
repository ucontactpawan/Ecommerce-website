
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">

    <!-- Conditional Category CSS -->
    <?php
    $uri = service('uri');
    if ($uri->getSegment(1) === 'category') :
    ?>
        <link rel="stylesheet" href="<?= base_url('css/category.css') ?>">
    <?php endif; ?>

    <script>
        window.siteUrl = '<?= rtrim(site_url(), '/') ?>';
    </script>
</head>
<body>

<!-- Include the navbar -->
<?= view('templates/navbar') ?>

<div class="container mt-4"></div>