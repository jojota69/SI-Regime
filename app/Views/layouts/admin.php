<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - HealthyRegime' : 'Panneau Admin - HealthyRegime' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if (isset($styles)): ?>
        <style><?= $styles ?></style>
    <?php endif; ?>
</head>
<body>
    <?php echo view('partials/admin_header'); ?>
    
    <main class="container mb-4">
        <?= $this->renderSection('content') ?>
    </main>
    
    <?php echo view('partials/footer'); ?>
</body>
</html>
