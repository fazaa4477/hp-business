<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#8ed8ff">
    <meta name="description" content="Sistem Manajemen Bisnis HP Second berbasis PHP & MySQL dengan IMEI Tracking.">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23FFE66D%22 stroke=%22%23101010%22 stroke-width=%2210%22/><text y=%2265%22 font-size=%2260%22 font-family=%22monospace%22 font-weight=%22bold%22 x=%2222%22 fill=%22%23101010%22>HP</text></svg>">
    <title><?= e($pageTitle ?? 'HP Business') ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/app.css')) ?>">
</head>
<body>
<?= $content ?>
<script src="<?= e(url('assets/js/app.js')) ?>"></script>
</body>
</html>
