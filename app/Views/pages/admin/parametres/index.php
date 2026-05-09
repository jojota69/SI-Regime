<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parametres - Admin</title>
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
                    <h1>⚙️ Parametres</h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / Parametres</div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="/admin/parametres/new" class="btn btn-accent">+ Nouveau parametre</a>
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
            <?php if (!empty($parametres)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Cle</th>
                            <th>Valeur</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parametres as $parametre): ?>
                            <?php $key = (string) $parametre['cle']; ?>
                            <tr>
                                <td><strong><?= esc($key) ?></strong></td>
                                <td><?= esc($parametre['valeur']) ?></td>
                                <td><?= esc($parametre['description'] ?? '—') ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="/admin/parametres/<?= esc(rawurlencode($key)) ?>/edit" class="btn btn-secondary btn-small">Modifier</a>
                                        <form action="/admin/parametres/<?= esc(rawurlencode($key)) ?>/delete" method="post" onsubmit="return confirm('Supprimer ce parametre ?');">
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
                    <div style="font-size: 2rem;">⚙️</div>
                    <h3>Aucun parametre</h3>
                    <p>Ajoutez votre premier parametre systeme.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
