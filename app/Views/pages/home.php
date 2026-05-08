<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>Bienvenue</h1>

    <?php if (!empty($profil) && isset($profil['imc_actuel'])): ?>
        <p>Votre IMC actuel: <strong><?= esc($profil['imc_actuel']) ?></strong></p>
    <?php else: ?>
        <p>Votre IMC n'est pas encore disponible.</p>
    <?php endif; ?>

    <?php if (!empty($walletMessage)): ?>
        <p><?= esc($walletMessage) ?></p>
    <?php endif; ?>
    <?php if (!empty($walletError)): ?>
        <p><?= esc($walletError) ?></p>
    <?php endif; ?>

    <h2>Porte monnaie</h2>
    <p>Solde: <strong><?= esc($user['solde_ariary'] ?? 0) ?></strong> Ar</p>
    <p>Statut Gold: <strong><?= (!empty($user) && (int) $user['est_gold'] === 1) ? 'Actif' : 'Inactif' ?></strong></p>

    <form action="/home/recharge" method="post">
        <label for="code">Code de recharge:</label>
        <input type="text" name="code" id="code" required>
        <button type="submit">Recharger</button>
    </form>

    <?php if (empty($user) || (int) $user['est_gold'] !== 1): ?>
        <form action="/home/gold" method="post">
            <button type="submit">Activer Gold (<?= esc($goldPrice ?? 100000) ?> Ar)</button>
        </form>
    <?php endif; ?>

    <?php if (empty($userObjectif)): ?>
        <h2>Completer votre profil</h2>
        <form action="/home/objectif" method="post">
            <label for="objectif_id">Selection d'objectif:</label>
            <select name="objectif_id" id="objectif_id" required>
                <option value="" disabled selected>Choisir...</option>
                <?php if (!empty($objectifs)): ?>
                    <?php foreach ($objectifs as $item): ?>
                        <option value="<?= esc($item['id']) ?>"><?= esc($item['nom']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="submit">Valider</button>
        </form>
    <?php else: ?>
        <h2>Votre objectif</h2>
        <p><?= esc($objectif['nom'] ?? 'Objectif non defini') ?></p>

        <?php if (!empty($suggestion)): ?>
            <h3>Suggestion de regime et activite</h3>
            <p><strong>Regime:</strong> <?= esc($suggestion['nom']) ?></p>
            <?php if (!empty($suggestion['description'])): ?>
                <p><?= esc($suggestion['description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($suggestion['activite_nom'])): ?>
                <p><strong>Activite sportive:</strong> <?= esc($suggestion['activite_nom']) ?></p>
                <?php if (!empty($suggestion['intensite'])): ?>
                    <p><strong>Intensite:</strong> <?= esc($suggestion['intensite']) ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p>Aucune activite sportive liee a ce regime.</p>
            <?php endif; ?>

            <?php if (!empty($dureeJours)): ?>
                <p><strong>Duree estimee:</strong> <?= esc($dureeJours) ?> jours</p>
            <?php endif; ?>

            <?php if ($prixFinal !== null): ?>
                <p><strong>Prix pour <?= esc($prixDuree) ?> jours:</strong> <?= esc($prixFinal) ?> Ar</p>
                <?php if ($prixBase !== null && $prixFinal != $prixBase): ?>
                    <p>Prix normal: <?= esc($prixBase) ?> Ar (remise Gold)</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Aucun prix defini pour cette duree.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Aucune suggestion disponible pour cet objectif.</p>
        <?php endif; ?>

        <form action="/home/objectif" method="post">
            <label for="objectif_id">Changer d'objectif:</label>
            <select name="objectif_id" id="objectif_id" required>
                <?php if (!empty($objectifs)): ?>
                    <?php foreach ($objectifs as $item): ?>
                        <option value="<?= esc($item['id']) ?>" <?= (!empty($userObjectif) && (int) $userObjectif['objectif_id'] === (int) $item['id']) ? 'selected' : '' ?>>
                            <?= esc($item['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="submit">Mettre a jour</button>
        </form>
    <?php endif; ?>

    <p><a href="/home/export/pdf">Exporter en PDF</a></p>
    <a href="/logout">Se déconnecter</a>
</body>
</html>