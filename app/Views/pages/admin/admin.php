<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau Admin - HealthyRegime</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--text-primary) 0%, #374151 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .admin-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-logo {
            font-size: 1.75rem;
            font-weight: 800;
        }
        
        .admin-breadcrumb {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }
        
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border-left: 4px solid var(--primary);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        
        .admin-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }
        
        .admin-card.warning {
            border-left-color: var(--accent);
        }
        
        .admin-card.danger {
            border-left-color: var(--danger);
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .card-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        .section-panel {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--bg-tertiary);
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-header-content">
                <div>
                    <div class="admin-logo">⚙️ Panneau Admin</div>
                    <div class="admin-breadcrumb">HealthyRegime - Gestion Administrative</div>
                </div>
                <a href="/logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>
    </div>
    
    <main class="container mb-4">
        <div class="admin-grid">
            <a href="/admin/codes" class="admin-card">
                <div class="card-icon">📋</div>
                <div class="card-title">Gérer les codes</div>
                <div class="card-description">
                    Visualisez et gérez tous les codes de portefeuille du système
                </div>
            </a>

            <a href="/admin/regimes" class="admin-card">
                <div class="card-icon">🥗</div>
                <div class="card-title">CRUD des regimes</div>
                <div class="card-description">
                    Creez, modifiez et organisez les regimes et leurs prix
                </div>
            </a>

            <a href="/admin/activites" class="admin-card">
                <div class="card-icon">🏃</div>
                <div class="card-title">CRUD des activites</div>
                <div class="card-description">
                    Gere la liste des activites sportives associees
                </div>
            </a>
            
            <a href="/admin/parametres" class="admin-card warning">
                <div class="card-icon">⚙️</div>
                <div class="card-title">Parametres</div>
                <div class="card-description">
                    Accedez aux parametres et configurations du systeme
                </div>
            </a>
            
            <a href="/admin/stats" class="admin-card danger">
                <div class="card-icon">📊</div>
                <div class="card-title">Statistiques</div>
                <div class="card-description">
                    Consultez les statistiques et rapports du système
                </div>
            </a>
        </div>
        
        <div class="section-panel">
            <div class="section-title">📝 Bienvenue Admin</div>
            <p>
                Vous avez accès à toutes les fonctionnalités d'administration de HealthyRegime. 
                Utilisez les cartes ci-dessus pour naviguer vers les différentes sections.
            </p>
            <ul style="margin-top: 1rem; margin-left: 1.5rem;">
                <li style="margin-bottom: 0.75rem;">👉 <strong>Gérer les codes</strong> - Visualisez tous les codes de portefeuille</li>
                <li style="margin-bottom: 0.75rem;">👉 <strong>Regimes & activites</strong> - Gere la composition, les prix et les associations</li>
                <li>👉 <strong>Statistiques</strong> - Tableaux de bord et rapports d'analyse</li>
            </ul>
        </div>
    </main>
    
    <footer>
        <div class="container text-center">
            <p>&copy; 2026 HealthyRegime - Panneau d'Administration</p>
            <p style="font-size: 0.875rem; color: #999;">Dernière connexion: <?= date('d/m/Y H:i') ?></p>
        </div>
    </footer>
</body>
</html>