<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #e0f2fe 0%, #f8fafc 45%, #ecfeff 100%);
        }

        h1, h2 {
            font-family: 'DM Serif Display', serif;
            letter-spacing: 0.5px;
        }

        .hero {
            background: linear-gradient(120deg, #0f172a, #0ea5e9 60%, #14b8a6);
            color: white;
            padding: 2.5rem 0;
            border-radius: var(--radius-lg);
            margin: 2rem auto;
        }

        .hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border-left: 4px solid #0ea5e9;
        }

        .stat-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-top: 0.5rem;
            color: var(--text-primary);
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        .pivot-table {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .pivot-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .pivot-table th, .pivot-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .pivot-table thead {
            background: #f1f5f9;
        }

        .pivot-table tbody tr:hover {
            background: var(--bg-secondary);
        }

        .metric-note {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <div class="container hero-content">
                <div>
                    <h1>📊 Tableau de bord</h1>
                    <p style="color: rgba(255,255,255,0.85);">Vue rapide des indicateurs administratifs.</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="/admin" class="btn btn-outline" style="border-color: white; color: white;">Retour</a>
                </div>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Utilisateurs</div>
                <div class="stat-value"><?= number_format($stats['users_total'] ?? 0, 0, ',', ' ') ?></div>
                <div class="metric-note"><?= number_format($stats['admins_total'] ?? 0, 0, ',', ' ') ?> admins</div>
            </div>
            <div class="stat-card" style="border-left-color: #14b8a6;">
                <div class="stat-title">Regimes</div>
                <div class="stat-value"><?= number_format($stats['regimes_total'] ?? 0, 0, ',', ' ') ?></div>
                <div class="metric-note">Actifs dans le catalogue</div>
            </div>
            <div class="stat-card" style="border-left-color: #6366f1;">
                <div class="stat-title">Activites</div>
                <div class="stat-value"><?= number_format($stats['activites_total'] ?? 0, 0, ',', ' ') ?></div>
                <div class="metric-note">Associables aux regimes</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-title">Codes portefeuille</div>
                <div class="stat-value"><?= number_format($stats['codes_total'] ?? 0, 0, ',', ' ') ?></div>
                <div class="metric-note">Disponibles: <?= number_format($stats['codes_disponibles'] ?? 0, 0, ',', ' ') ?></div>
            </div>
            <div class="stat-card" style="border-left-color: #ef4444;">
                <div class="stat-title">Revenus souscriptions</div>
                <div class="stat-value"><?= number_format($stats['revenu_souscriptions'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="metric-note">Gold: <?= number_format($stats['revenu_gold'] ?? 0, 0, ',', ' ') ?> Ar</div>
            </div>
        </section>

        <section class="chart-grid">
            <div class="chart-card">
                <h2>Regimes par objectif</h2>
                <canvas id="regimesChart" height="180"></canvas>
            </div>
            <div class="chart-card">
                <h2>Activites par objectif</h2>
                <canvas id="activitesChart" height="180"></canvas>
            </div>
            <div class="chart-card">
                <h2>Codes portefeuille</h2>
                <canvas id="codesChart" height="180"></canvas>
                <div class="metric-note">Valides: <?= number_format($stats['codes_valides'] ?? 0, 0, ',', ' ') ?>, en attente: <?= number_format($stats['codes_pending'] ?? 0, 0, ',', ' ') ?>.</div>
            </div>
        </section>

        <section class="pivot-table">
            <table>
                <thead>
                    <tr>
                        <th>Objectif</th>
                        <th>Regimes</th>
                        <th>Activites</th>
                        <th>Souscriptions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pivotRows as $row): ?>
                        <tr>
                            <td><?= esc($row['objectif']) ?></td>
                            <td><?= esc($row['regimes']) ?></td>
                            <td><?= esc($row['activites']) ?></td>
                            <td><?= esc($row['souscriptions']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = <?= json_encode($chartLabels ?? []) ?>;
        const regimesData = <?= json_encode($chartRegimes ?? []) ?>;
        const activitesData = <?= json_encode($chartActivites ?? []) ?>;
        const codesData = [
            <?= (int) ($stats['codes_disponibles'] ?? 0) ?>,
            <?= (int) ($stats['codes_used'] ?? 0) ?>,
            <?= (int) ($stats['codes_pending'] ?? 0) ?>
        ];

        const palette = {
            blue: '#0ea5e9',
            teal: '#14b8a6',
            indigo: '#6366f1',
            amber: '#f59e0b',
            red: '#ef4444'
        };

        new Chart(document.getElementById('regimesChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Regimes',
                    data: regimesData,
                    backgroundColor: palette.blue,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('activitesChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Activites',
                    data: activitesData,
                    backgroundColor: palette.teal,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('codesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Utilises', 'En attente'],
                datasets: [{
                    data: codesData,
                    backgroundColor: [palette.indigo, palette.amber, palette.red]
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</body>
</html>
