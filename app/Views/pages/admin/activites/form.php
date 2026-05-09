<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($activite['id']) ? 'Modifier activite' : 'Nouvelle activite' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%);
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
                    <h1><?= !empty($activite['id']) ? 'Modifier une activite' : 'Nouvelle activite' ?></h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / <a href="/admin/activites" style="color: inherit;">Activites</a></div>
                </div>
                <a href="/admin/activites" class="btn btn-outline" style="border-color: white; color: white;">Retour</a>
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

            <?php $isEdit = !empty($activite['id']); ?>
            <form action="<?= $isEdit ? '/admin/activites/' . esc($activite['id']) . '/update' : '/admin/activites' ?>" method="post">
                <div class="form-group">
                    <label for="nom">Nom de l'activite</label>
                    <input type="text" id="nom" name="nom" value="<?= esc($activite['nom'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="objectif_id">Objectif (optionnel)</label>
                    <select id="objectif_id" name="objectif_id">
                        <option value="">Aucun objectif</option>
                        <?php foreach ($objectifs as $objectif): ?>
                            <option value="<?= esc($objectif['id']) ?>" <?= (!empty($activite['objectif_id']) && (int) $activite['objectif_id'] === (int) $objectif['id']) ? 'selected' : '' ?>>
                                <?= esc($objectif['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="/admin/activites" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
