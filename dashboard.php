<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Programme SI-Regime</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="dashboard-container">
        <nav class="top-nav">
            <span class="logo">SI-Regime 🥗</span>
            <div class="nav-right">
                <span class="wallet-badge">Porte-monnaie: 50.00 €</span>
                <a href="login.php" class="logout-btn">Quitter</a>
            </div>
        </nav>

        <header class="welcome-section">
            <h1>Bonjour, <strong>Utilisateur</strong> !</h1>
            <p>Voici votre bilan et votre programme personnalisé du jour.</p>
        </header>

        <div class="main-layout">
            <aside class="side-stats">
                <div class="glass-card imc-highlight">
                    <h3>Votre IMC</h3>
                    <div class="circle-chart">
                        <span class="imc-number">24.5</span>
                    </div>
                    <p class="status-label">Poids de forme</p>
                </div>
                
                <div class="glass-card gold-banner">
                    <h4>Option Gold</h4>
                    <p>-15% sur tout le site</p>
                    <button class="btn-gold-action">Activer maintenant</button>
                </div>
            </aside>

            <!-- Contenu Central : Objectifs & Actions -->
            <main class="content-area">
                <section class="glass-card">
                    <h2>Quelle est votre priorité aujourd'hui ?</h2>
                    <form action="suggestions.php" method="GET" class="goal-selector">
                        <div class="goal-grid">
                            <label class="goal-card">
                                <input type="radio" name="objectif" value="reduire" checked>
                                <div class="goal-content">
                                    <span class="icon">📉</span>
                                    <span class="text">Perdre du poids</span>
                                </div>
                            </label>
                            <label class="goal-card">
                                <input type="radio" name="objectif" value="augmenter">
                                <div class="goal-content">
                                    <span class="icon">📈</span>
                                    <span class="text">Prendre du poids</span>
                                </div>
                            </label>
                            <label class="goal-card">
                                <input type="radio" name="objectif" value="ideal">
                                <div class="goal-content">
                                    <span class="icon">✨</span>
                                    <span class="text">Stabiliser (IMC Idéal)</span>
                                </div>
                            </label>
                        </div>
                        <button type="submit" class="btn-cta">Démarrer mon programme personnalisé</button>
                    </form>
                </section>

                <div class="action-footer">
                    <button class="btn-pdf">📥 Télécharger mon guide PDF</button>
                </div>
            </main>
        </div>
    </div>

</body>
</html>