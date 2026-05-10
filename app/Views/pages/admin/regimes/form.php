<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($regime['id']) ? 'Modifier regime' : 'Nouveau regime' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #0f766e 0%, #0ea5e9 100%);
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-table th, .price-table td {
            border-bottom: 1px solid var(--border);
            padding: 0.75rem;
            text-align: left;
        }

        .alert {
            background: #fef2f2;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
        }

        .helper-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-header-content">
                <div>
                    <h1><?= !empty($regime['id']) ? 'Modifier un regime' : 'Nouveau regime' ?></h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / <a href="/admin/regimes" style="color: inherit;">Regimes</a></div>
                </div>
                <a href="/admin/regimes" class="btn btn-outline" style="border-color: white; color: white;">Retour</a>
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

            <?php $isEdit = !empty($regime['id']); ?>
            <form action="<?= $isEdit ? '/admin/regimes/' . esc($regime['id']) . '/update' : '/admin/regimes' ?>" method="post">
                <div class="form-group">
                    <label for="nom">Nom du regime</label>
                    <input type="text" id="nom" name="nom" value="<?= esc($regime['nom'] ?? '') ?>" required>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="pct_viande">% Viande</label>
                        <input type="number" step="0.1" id="pct_viande" name="pct_viande" value="<?= esc($regime['pct_viande'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pct_poisson">% Poisson</label>
                        <input type="number" step="0.1" id="pct_poisson" name="pct_poisson" value="<?= esc($regime['pct_poisson'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pct_volaille">% Volaille</label>
                        <input type="number" step="0.1" id="pct_volaille" name="pct_volaille" value="<?= esc($regime['pct_volaille'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="helper-text">Le total des pourcentages doit etre egal a 100%.</div>

                <div class="form-grid" style="margin-top: 1rem;">
                    <div class="form-group">
                        <label for="variation_poids_kg">Variation poids (kg)</label>
                        <input type="number" step="0.1" id="variation_poids_kg" name="variation_poids_kg" value="<?= esc($regime['variation_poids_kg'] ?? '') ?>" required>
                        <div class="helper-text">Valeur negative pour perte de poids.</div>
                    </div>
                    <div class="form-group">
                        <label for="duree_standard_jours">Duree standard (jours)</label>
                        <input type="number" id="duree_standard_jours" name="duree_standard_jours" value="<?= esc($regime['duree_standard_jours'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="objectif_id">Objectif</label>
                        <select id="objectif_id" name="objectif_id" required>
                            <option value="">Selectionner...</option>
                            <?php foreach ($objectifs as $objectif): ?>
                                <option value="<?= esc($objectif['id']) ?>" <?= (!empty($regime['objectif_id']) && (int) $regime['objectif_id'] === (int) $objectif['id']) ? 'selected' : '' ?>>
                                    <?= esc($objectif['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Activites associees</label>
                    <div class="checkbox-grid">
                        <?php foreach ($activites as $activite): ?>
                            <?php $checked = in_array((int) $activite['id'], $selectedActivites ?? [], true); ?>
                            <label>
                                <input type="checkbox" name="activite_ids[]" value="<?= esc($activite['id']) ?>" <?= $checked ? 'checked' : '' ?>>
                                <?= esc($activite['nom']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label>Prix selon la duree</label>
                    <div class="helper-text" style="margin-bottom: 0.5rem;">Ajoutez une ou plusieurs durees avec leur prix correspondant.</div>
                    <table class="price-table">
                        <thead>
                            <tr>
                                <th>Duree (jours)</th>
                                <th>Prix (Ar)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="price-rows">
                            <?php foreach ($prixRows as $row): ?>
                                <tr>
                                    <td><input type="number" name="duree_jours[]" value="<?= esc($row['duree_jours']) ?>" min="1"></td>
                                    <td><input type="number" step="0.01" name="prix_ariary[]" value="<?= esc($row['prix_ariary']) ?>" min="1"></td>
                                    <td><button type="button" class="btn btn-outline btn-small remove-row">Retirer</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" id="add-price-row" class="btn btn-secondary btn-small" style="margin-top: 0.75rem;">+ Ajouter une ligne</button>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="/admin/regimes" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const addButton = document.getElementById('add-price-row');
        const priceBody = document.getElementById('price-rows');

        addButton.addEventListener('click', () => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="number" name="duree_jours[]" min="1"></td>
                <td><input type="number" step="0.01" name="prix_ariary[]" min="1"></td>
                <td><button type="button" class="btn btn-outline btn-small remove-row">Retirer</button></td>
            `;
            priceBody.appendChild(row);
        });

        document.addEventListener('click', (event) => {
            if (!event.target.classList.contains('remove-row')) {
                return;
            }

            const rows = priceBody.querySelectorAll('tr');
            if (rows.length <= 1) {
                return;
            }

            event.target.closest('tr').remove();
        });
    </script>
</body>
</html>
