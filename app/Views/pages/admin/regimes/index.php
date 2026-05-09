<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regimes - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #0f172a 0%, #1f2937 60%, #0f766e 100%);
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

        .page-title {
            margin: 0;
        }

        .breadcrumb {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .table-container {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--bg-tertiary);
            border-bottom: 2px solid var(--border);
        }

        th, td {
            padding: 1rem;
            text-align: left;
        }

        tbody tr:hover {
            background-color: var(--bg-secondary);
        }

        .inline-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .inline-pill {
            background: var(--bg-tertiary);
            border-radius: var(--radius-full);
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }

        .alert-success { background: #ecfdf5; color: #065f46; }
        .alert-danger { background: #fef2f2; color: #991b1b; }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-header-content">
                <div>
                    <h1 class="page-title">🥗 Regimes</h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / Regimes</div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="/admin/regimes/new" class="btn btn-accent">+ Nouveau regime</a>
                    <a href="/admin" class="btn btn-outline" style="border-color: white; color: white;">Retour</a>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (!empty($regimes)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Objectif</th>
                            <th>Composition</th>
                            <th>Variation</th>
                            <th>Duree standard</th>
                            <th>Prix par duree</th>
                            <th>Activites</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regimes as $regime): ?>
                            <?php $regimeId = (int) $regime['id']; ?>
                            <tr>
                                <td><strong><?= esc($regime['nom']) ?></strong></td>
                                <td><?= esc($regime['objectif_nom'] ?? '—') ?></td>
                                <td style="font-size: 0.875rem; color: var(--text-secondary);">
                                    <?= esc($regime['pct_viande']) ?>% viande,
                                    <?= esc($regime['pct_poisson']) ?>% poisson,
                                    <?= esc($regime['pct_volaille']) ?>% volaille
                                </td>
                                <td><?= esc($regime['variation_poids_kg']) ?> kg</td>
                                <td><?= esc($regime['duree_standard_jours']) ?> j</td>
                                <td>
                                    <?php if (!empty($prixByRegime[$regimeId])): ?>
                                        <ul class="inline-list">
                                            <?php foreach ($prixByRegime[$regimeId] as $prix): ?>
                                                <li class="inline-pill"><?= esc($prix['duree_jours']) ?>j : <?= number_format($prix['prix_ariary'], 0, ',', ' ') ?> Ar</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span style="color: var(--text-light);">Aucun prix</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($activitesByRegime[$regimeId])): ?>
                                        <ul class="inline-list">
                                            <?php foreach ($activitesByRegime[$regimeId] as $activite): ?>
                                                <li class="inline-pill"><?= esc($activite) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span style="color: var(--text-light);">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="/admin/regimes/<?= esc($regimeId) ?>/edit" class="btn btn-secondary btn-small">Modifier</a>
                                        <form action="/admin/regimes/<?= esc($regimeId) ?>/delete" method="post" onsubmit="return confirm('Supprimer ce regime ?');">
                                            <button type="submit" class="btn btn-danger btn-small">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 2rem;">🥗</div>
                    <h3>Aucun regime</h3>
                    <p>Commencez par ajouter un nouveau regime.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
