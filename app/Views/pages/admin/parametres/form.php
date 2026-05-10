<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($parametre['cle']) ? 'Modifier parametre' : 'Nouveau parametre' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #111827 0%, #f59e0b 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: var(--radius-lg);
        }

        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .form-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        .alert {
            background: #fef2f2;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-header-content">
                <div>
                    <h1><?= !empty($parametre['cle']) ? 'Modifier un parametre' : 'Nouveau parametre' ?></h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / <a href="/admin/parametres" style="color: inherit;">Parametres</a></div>
                </div>
                <a href="/admin/parametres" class="btn btn-outline" style="border-color: white; color: white;">Retour</a>
            </div>
        </div>

        <div class="form-card">
            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <strong>Veuillez corriger les erreurs :</strong>
                    <ul style="margin-top: 0.5rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php $isEdit = !empty($parametre['cle']); ?>
            <form action="<?= $isEdit ? '/admin/parametres/' . esc(rawurlencode($parametre['cle'])) . '/update' : '/admin/parametres' ?>" method="post">
                <div class="form-group">
                    <label for="cle">Cle</label>
                    <input type="text" id="cle" name="cle" value="<?= esc($parametre['cle'] ?? '') ?>" <?= $isEdit ? 'readonly' : '' ?> required>
                </div>

                <div class="form-group">
                    <label for="valeur">Valeur</label>
                    <input type="text" id="valeur" name="valeur" value="<?= esc($parametre['valeur'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" value="<?= esc($parametre['description'] ?? '') ?>">
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="/admin/parametres" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
