<?php $this->extend('layouts/auth'); ?>

<?php $this->section('content'); ?>

<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }
    
    .login-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        box-shadow: var(--shadow-xl);
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .login-logo {
        font-size: 2.5rem;
        color: var(--primary);
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    
    .errors {
        list-style: none;
        padding: 1rem;
        background-color: #FEE2E2;
        border-left: 4px solid var(--danger);
        border-radius: var(--radius);
        margin-bottom: 1rem;
        color: #7F1D1D;
    }
    
    .errors li {
        margin-bottom: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .btn-login {
        width: 100%;
        margin-top: 1.5rem;
    }
    
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    
    .login-footer a {
        color: var(--primary);
        font-weight: 600;
    }
</style>

<div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">🥗</div>
                <h1 style="margin: 0;">HealthyRegime</h1>
                <p class="login-subtitle">Votre assistant de régime personnel</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= esc($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($validation) && ($validation->hasError('mail') || $validation->hasError('password'))): ?>
                <ul class="errors">
                    <?php if ($validation->hasError('mail')): ?>
                        <li>📧 <?= esc($validation->getError('mail')) ?></li>
                    <?php endif; ?>
                    <?php if ($validation->hasError('password')): ?>
                        <li>🔐 <?= esc($validation->getError('password')) ?></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
            
            <form action="/login" method="post">
                <div class="form-group">
                    <label for="mail">Adresse email</label>
                    <input type="email" name="mail" id="mail" placeholder="votre@email.com" value="<?= esc($mail ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">Se connecter</button>
            </form>
            
            <div class="login-footer">
                Pas encore inscrit? <a href="/inscription">Créer un compte</a>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>