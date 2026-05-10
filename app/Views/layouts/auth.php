<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - HealthyRegime' : 'HealthyRegime' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if (isset($styles)): ?>
        <style><?= $styles ?></style>
    <?php endif; ?>
</head>
<body>
    <?= $this->renderSection('content') ?>
</body>
</html>
