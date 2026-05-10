<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<style>
    .hero {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: var(--radius-lg);
    }
    
    .hero-content {
        text-align: center;
    }
    
    .hero h1 {
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .hero p {
        color: rgba(255,255,255,0.9);
        font-size: 1.125rem;
    }
    
    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 1024px) {
        .main-content {
            grid-template-columns: 1fr;
        }
    }
    
    .section {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--bg-tertiary);
    }
    
    .wallet-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: var(--radius);
    }
    
    .wallet-item {
        text-align: center;
    }
    
    .wallet-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .wallet-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .suggestion-box {
        background: linear-gradient(135deg, #F0FDF4 0%, #DBEAFE 100%);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        border-left: 4px solid var(--primary);
        margin-bottom: 1.5rem;
    }
    
    .suggestion-header {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .suggestion-item {
        margin-bottom: 1rem;
    }
    
    .suggestion-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .suggestion-value {
        color: var(--text-secondary);
    }
    
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
    }
</style>
        <?php if (!empty($walletMessage)): ?>
            <div class="alert alert-success mt-3">
                ✓ <?= esc($walletMessage) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($walletError)): ?>
            <div class="alert alert-danger mt-3">
                ✗ <?= esc($walletError) ?>
            </div>
        <?php endif; ?>
        
        <div class="hero">
            <div class="hero-content container">
                <h1>Bienvenue sur HealthyRegime</h1>
                <p>Votre assistant personnel pour un régime sain et équilibré</p>
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value">
                    <?php if (!empty($profil) && isset($profil['imc_actuel'])): ?>
                        <?= esc($profil['imc_actuel']) ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </div>
                <div class="stat-label">IMC Actuel</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value" style="color: var(--accent);">
                    <?= number_format($user['solde_ariary'] ?? 0, 0, '.', ' ') ?>
                </div>
                <div class="stat-label">Solde (Ar)</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">
                    <?php if (!empty($user) && (int) $user['est_gold'] === 1): ?>
                        <span style="color: var(--accent);">✓</span>
                    <?php else: ?>
                        <span style="color: #999;">✗</span>
                    <?php endif; ?>
                </div>
                <div class="stat-label">Statut Gold</div>
            </div>
        </div>
        
        <div class="main-content mt-4">
            <div>
                <?php if (empty($userObjectif)): ?>
                    <!-- Pas encore d'objectif -->
                    <div class="section">
                        <div class="section-title">📋 Définir votre objectif</div>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Commencez par sélectionner votre objectif de santé pour recevoir des recommandations personnalisées.
                        </p>
                        
                        <form action="/home/objectif" method="post" class="form-group">
                            <label for="objectif_id">Choisissez votre objectif</label>
                            <select name="objectif_id" id="objectif_id" required>
                                <option value="" disabled selected>Sélectionner un objectif...</option>
                                <?php if (!empty($objectifs)): ?>
                                    <?php foreach ($objectifs as $item): ?>
                                        <option value="<?= esc($item['id']) ?>">
                                            <?= esc($item['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-primary mt-3">Confirmer mon objectif</button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Avec objectif -->
                    <div class="section">
                        <div class="section-title">🎯 Votre objectif</div>
                        <div class="suggestion-box">
                            <div style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem;">
                                <?= esc($objectif['nom'] ?? 'Objectif non défini') ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($suggestion)): ?>
                            <div class="section-title">💡 Suggestion personnalisée</div>
                            <div class="suggestion-box">
                                <div class="suggestion-header">
                                    🍽️ Régime recommandé
                                </div>
                                <div class="suggestion-item">
                                    <div class="suggestion-label"><?= esc($suggestion['nom']) ?></div>
                                    <?php if (!empty($suggestion['description'])): ?>
                                        <div class="suggestion-value"><?= esc($suggestion['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($suggestion['activite_nom'])): ?>
                                    <div class="suggestion-item mt-2">
                                        <div class="suggestion-label">🏃 Activité sportive</div>
                                        <div class="suggestion-value">
                                            <?= esc($suggestion['activite_nom']) ?>
                                            <?php if (!empty($suggestion['intensite'])): ?>
                                                - Intensité: <?= esc($suggestion['intensite']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="suggestion-item mt-2" style="color: var(--text-secondary);">
                                        Pas d'activité sportive associée
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($dureeJours)): ?>
                                    <div class="suggestion-item mt-2">
                                        <div class="suggestion-label">⏱️ Durée estimée</div>
                                        <div class="suggestion-value"><?= esc($dureeJours) ?> jours</div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($prixFinal !== null): ?>
                                    <div class="suggestion-item mt-2">
                                        <div class="suggestion-label">💰 Coût pour <?= esc($prixDuree) ?> jours</div>
                                        <div class="suggestion-value" style="font-size: 1.25rem; color: var(--primary); font-weight: 700;">
                                            <?= esc($prixFinal) ?> Ar
                                        </div>
                                        <?php if ($prixBase !== null && $prixFinal != $prixBase): ?>
                                            <div style="font-size: 0.875rem; color: var(--accent); margin-top: 0.5rem;">
                                                ✨ Prix normal: <?= esc($prixBase) ?> Ar (avec remise Gold)
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Aucune suggestion disponible pour cet objectif pour le moment.
                            </div>
                        <?php endif; ?>
                        
                        <form action="/home/objectif" method="post" class="section mt-3">
                            <label for="objectif_id_change">Changer d'objectif</label>
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <select name="objectif_id" id="objectif_id_change" required>
                                    <?php if (!empty($objectifs)): ?>
                                        <?php foreach ($objectifs as $item): ?>
                                            <option value="<?= esc($item['id']) ?>" 
                                                <?= (!empty($userObjectif) && (int) $userObjectif['objectif_id'] === (int) $item['id']) ? 'selected' : '' ?>>
                                                <?= esc($item['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <button type="submit" class="btn btn-secondary btn-small" style="width: 100%;">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Portefeuille -->
                <div class="section">
                    <div class="section-title">💳 Mon Portefeuille</div>
                    
                    <div class="wallet-status">
                        <div class="wallet-item">
                            <div class="wallet-value" style="color: var(--accent);">
                                <?= number_format($user['solde_ariary'] ?? 0, 0, '.', ' ') ?> Ar
                            </div>
                            <div class="wallet-label">Solde</div>
                        </div>
                        <div style="height: 40px; width: 1px; background: var(--border);"></div>
                        <div class="wallet-item">
                            <div class="wallet-value">
                                <?php if (!empty($user) && (int) $user['est_gold'] === 1): ?>
                                    <span style="color: var(--accent);">Actif ✓</span>
                                <?php else: ?>
                                    <span style="color: #999;">Inactif</span>
                                <?php endif; ?>
                            </div>
                            <div class="wallet-label">Gold</div>
                        </div>
                    </div>
                    
                    <form action="/home/recharge" method="post" class="form-group">
                        <label for="code">Code de recharge</label>
                        <input type="text" name="code" id="code" placeholder="Entrez votre code" required>
                        <button type="submit" class="btn btn-primary mt-2">Recharger</button>
                    </form>
                    
                    <?php if (empty($user) || (int) $user['est_gold'] !== 1): ?>
                        <form action="/home/gold" method="post">
                            <button type="submit" class="btn btn-accent" style="width: 100%;">
                                ✨ Activer Gold (<?= esc($goldPrice ?? 100000) ?> Ar)
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
                <!-- Actions -->
                <div class="section">
                    <div class="section-title">📊 Actions</div>
                    <div class="action-buttons">
                        <a href="/home/export/pdf" class="btn btn-secondary">
                            📥 Exporter en PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

<?php $this->endSection(); ?>