<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les codes - HealthyRegime</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: var(--radius-lg);
        }
        
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            margin: 0;
        }
        
        .breadcrumb {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            text-align: center;
            box-shadow: var(--shadow-md);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stat-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
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
        
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        tbody tr:hover {
            background-color: var(--bg-secondary);
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .badge-validated {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .badge-used {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        
        .badge-available {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .validation-section {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border-left: 4px solid var(--accent);
        }
        
        .validation-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 1.5rem;
            color: var(--text-primary);
        }
        
        .validation-form {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }
        
        .validation-form select {
            flex: 1;
            min-width: 250px;
        }
        
        .validation-form .btn {
            flex-shrink: 0;
        }
        
        @media (max-width: 768px) {
            .validation-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .validation-form select,
            .validation-form .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-header-content">
                <div>
                    <h1 class="page-title">📋 Gérer les codes</h1>
                    <div class="breadcrumb"><a href="/admin" style="color: inherit;">Admin</a> / Codes de portefeuille</div>
                </div>
                <a href="/admin" class="btn btn-outline" style="border-color: white; color: white;">← Retour</a>
            </div>
        </div>
        
        <?php if (!empty($codes)): ?>
            <div class="stats-section">
                <div class="stat-box">
                    <div class="stat-number"><?= count($codes) ?></div>
                    <div class="stat-text">Codes totaux</div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-number" style="color: var(--accent);">
                        <?= count(array_filter($codes, fn($c) => (int)$c['est_valide'] === 1 && (int)$c['est_utilise'] === 0)) ?>
                    </div>
                    <div class="stat-text">Codes disponibles</div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-number" style="color: var(--info);">
                        <?= count(array_filter($codes, fn($c) => (int)$c['est_utilise'] === 1)) ?>
                    </div>
                    <div class="stat-text">Codes utilisés</div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-number" style="color: var(--warning);">
                        <?= count(array_filter($codes, fn($c) => (int)$c['est_valide'] === 0)) ?>
                    </div>
                    <div class="stat-text">En attente de validation</div>
                </div>
            </div>
            
            <div class="validation-section">
                <h2 class="validation-title">✨ Valider un code</h2>
                <form class="validation-form" action="/admin/codes/valider" method="post">
                    <select name="code" id="code" required>
                        <option value="">Sélectionner un code à valider...</option>
                        <?php if (!empty($codesInvalide)): ?>
                            <?php foreach ($codesInvalide as $code): ?>
                                <option value="<?= esc($code['code']) ?>">
                                    <?= esc($code['code']) ?> (<?= number_format($code['montant'], 0, ',', ' ') ?> Ar)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Aucun code en attente</option>
                        <?php endif; ?>
                    </select>
                    <button type="submit" class="btn btn-accent">Valider</button>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Montant (Ar)</th>
                            <th>Validation</th>
                            <th>Utilisation</th>
                            <th>Utilisateur ID</th>
                            <th>Date création</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codes as $code): ?>
                            <tr>
                                <td>
                                    <strong style="font-family: monospace; font-size: 0.9rem;">
                                        <?= esc($code['code']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <strong><?= number_format($code['montant'], 0, ',', ' ') ?></strong>
                                </td>
                                <td>
                                    <?php if ((int)$code['est_valide'] === 1): ?>
                                        <span class="status-badge badge-validated">✓ Validé</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-pending">⏳ En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ((int)$code['est_utilise'] === 1): ?>
                                        <span class="status-badge badge-used">✓ Utilisé</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-available">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= !empty($code['user_id']) ? esc($code['user_id']) : '—' ?>
                                </td>
                                <td style="font-size: 0.875rem; color: var(--text-secondary);">
                                    <?= isset($code['created_at']) ? date('d/m/Y', strtotime($code['created_at'])) : '—' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="table-container">
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h3>Aucun code disponible</h3>
                    <p>Il n'y a pas encore de codes de portefeuille dans le système.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <footer style="margin-top: 4rem;">
        <div class="container text-center">
            <p>&copy; 2026 HealthyRegime - Gestion des codes</p>
        </div>
    </footer>
</body>
</html>