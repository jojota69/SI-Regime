<?php $this->extend('layouts/auth'); ?>

<?php $this->section('content'); ?>

<style>
    .inscription-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2rem 0;
    }
    
    .inscription-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        width: 100%;
        max-width: 450px;
        box-shadow: var(--shadow-xl);
    }
    
    .inscription-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .inscription-logo {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .inscription-title {
        margin: 0 0 0.5rem;
        color: var(--primary);
    }
    
    .inscription-subtitle {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
    }
    
    .progress-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
    }
    
    .step-marker {
        flex: 1;
        height: 4px;
        background-color: var(--bg-tertiary);
        border-radius: 2px;
        transition: all 0.3s;
    }
    
    .step-marker.active {
        background-color: var(--primary);
    }
    
    .hidden {
        display: none !important;
    }
    
    .error-msg {
        padding: 1rem;
        background-color: #FEE2E2;
        color: #7F1D1D;
        border-left: 4px solid var(--danger);
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .success-box {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #D1FAE5 0%, #DBEAFE 100%);
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
    }
    
    .success-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .success-title {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .success-message {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .button-group button {
        flex: 1;
    }
    
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    
    .login-link a {
        color: var(--primary);
        font-weight: 600;
    }
</style>

<div class="inscription-container">
    <div class="inscription-card">
        <div class="inscription-header">
            <div class="inscription-logo">🏃</div>
            <h1 class="inscription-title">Créer un compte</h1>
            <p class="inscription-subtitle">Rejoignez HealthyRegime et commencez votre parcours</p>
        </div>
        
        <!-- Étape 1 -->
        <div id="box-etape1">
                <div class="progress-indicator">
                    <div class="step-marker active"></div>
                    <div class="step-marker"></div>
                </div>
                
                <form id="formEtape1">
                    <?= csrf_field() ?>
                    
                    <div id="err1" class="error-msg hidden"></div>
                    
                    <h3 style="margin-top: 0;">Informations personnelles</h3>
                    
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre" required>
                            <option value="">Sélectionnez votre genre</option>
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Suivant →</button>
                </form>
                
                <div class="login-link">
                    Déjà inscrit? <a href="/">Se connecter</a>
                </div>
            </div>
            
            <!-- Étape 2 -->
            <div id="box-etape2" class="hidden">
                <div class="progress-indicator">
                    <div class="step-marker active"></div>
                    <div class="step-marker active"></div>
                </div>
                
                <form id="formEtape2">
                    <?= csrf_field() ?>
                    
                    <div id="err2" class="error-msg hidden"></div>
                    
                    <h3 style="margin-top: 0;">Informations de santé</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">
                        Ces informations nous aident à calculer votre IMC et vous proposer les meilleures recommandations.
                    </p>
                    
                    <div class="form-group">
                        <label for="taille">Taille (cm)</label>
                        <input type="number" id="taille" name="taille" placeholder="Ex: 175" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="poids">Poids (kg)</label>
                        <input type="number" id="poids" name="poids" placeholder="Ex: 70" step="0.01" required>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" onclick="basculer(1)" class="btn btn-outline" style="border-color: var(--border); color: var(--text-secondary);">← Retour</button>
                        <button type="submit" class="btn btn-primary">Finaliser</button>
                    </div>
                </form>
            </div>
            
            <!-- Succès -->
            <div id="box-success" class="hidden">
                <div class="success-box">
                    <div class="success-icon">✨</div>
                    <h2 class="success-title">Félicitations!</h2>
                    <p class="success-message">
                        Votre profil est complété et votre IMC a été calculé. Vous pouvez maintenant vous connecter.
                    </p>
                </div>
                
                <a href="/" class="btn btn-primary" style="width: 100%; display: block; text-align: center;">
                    Se connecter maintenant
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const urlBase = "<?= base_url() ?>";

    function basculer(etape) {
        document.getElementById('box-etape1').classList.toggle('hidden', etape !== 1);
        document.getElementById('box-etape2').classList.toggle('hidden', etape !== 2);
        document.getElementById('box-success').classList.toggle('hidden', etape !== 3);
    }

    document.getElementById('formEtape1').onsubmit = async (e) => {
        e.preventDefault();
        const errDiv = document.getElementById('err1');
        errDiv.classList.add('hidden');
        
        const res = await fetch(urlBase + '/user/validerEtape1', {
            method: 'POST',
            body: new FormData(e.target),
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        const json = await res.json();
        if(json.success) {
            basculer(2);
        } else {
            const errors = Object.values(json.errors);
            errDiv.innerHTML = '⚠️ ' + errors.join('<br>');
            errDiv.classList.remove('hidden');
        }
    };

    document.getElementById('formEtape2').onsubmit = async (e) => {
        e.preventDefault();
        const errDiv = document.getElementById('err2');
        errDiv.classList.add('hidden');
        
        const res = await fetch(urlBase + '/user/finaliser', {
            method: 'POST',
            body: new FormData(e.target),
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        const json = await res.json();
        if(json.success) {
            basculer(3);
        } else {
            const errors = Object.values(json.errors);
            errDiv.innerHTML = '⚠️ ' + errors.join('<br>');
            errDiv.classList.remove('hidden');
        }
    };
</script>

<?php $this->endSection(); ?>