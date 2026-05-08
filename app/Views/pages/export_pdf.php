<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport regime</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 6px; }
        h2 { font-size: 14px; margin: 16px 0 6px; }
        p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        .muted { color: #666; font-size: 10px; }
    </style>
</head>
<body>
    <h1>Rapport regime</h1>
    <p class="muted">Date: <?= esc($dateExport ?? '') ?></p>

    <h2>Profil utilisateur</h2>
    <table>
        <tr>
            <th>Nom</th>
            <td><?= esc($user['nom'] ?? '-') ?></td>
            <th>Prenom</th>
            <td><?= esc($user['prenom'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= esc($user['email'] ?? '-') ?></td>
            <th>Genre</th>
            <td><?= esc($user['genre'] ?? '-') ?></td>
        </tr>
    </table>

    <h2>Profil sante</h2>
    <table>
        <tr>
            <th>Taille (cm)</th>
            <td><?= esc($profil['taille_cm'] ?? '-') ?></td>
            <th>Poids (kg)</th>
            <td><?= esc($profil['poids_actuel_kg'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>IMC actuel</th>
            <td><?= esc($profil['imc_actuel'] ?? '-') ?></td>
            <th>Objectif</th>
            <td><?= esc($objectif['nom'] ?? '-') ?></td>
        </tr>
    </table>

    <h2>Suggestion regime</h2>
    <?php if (!empty($suggestion)): ?>
        <table>
            <tr>
                <th>Regime</th>
                <td><?= esc($suggestion['nom'] ?? '-') ?></td>
                <th>Variation poids (kg)</th>
                <td><?= esc($suggestion['variation_poids_kg'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Activite</th>
                <td><?= esc($suggestion['activite_nom'] ?? '-') ?></td>
                <th>Intensite</th>
                <td><?= esc($suggestion['intensite'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Duree estimee (jours)</th>
                <td><?= esc($dureeJours ?? '-') ?></td>
                <th>Prix</th>
                <td>
                    <?php if ($prixFinal !== null): ?>
                        <?= esc($prixFinal) ?> Ar
                        <?php if ($prixBase !== null && $prixFinal != $prixBase): ?>
                            (normal: <?= esc($prixBase) ?> Ar)
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php if (!empty($suggestion['description'])): ?>
            <p><?= esc($suggestion['description']) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>Aucune suggestion disponible.</p>
    <?php endif; ?>

    <h2>Porte monnaie</h2>
    <table>
        <tr>
            <th>Solde</th>
            <td><?= esc($user['solde_ariary'] ?? 0) ?> Ar</td>
            <th>Gold</th>
            <td><?= (!empty($user) && (int) $user['est_gold'] === 1) ? 'Actif' : 'Inactif' ?></td>
        </tr>
    </table>
</body>
</html>
