<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">🥗 HealthyRegime</div>
            <div class="header-right">
                <div class="user-info">
                    <div style="color: var(--text-primary); font-weight: 600;">Bienvenue!</div>
                    <div style="color: var(--text-secondary);">User ID: <?= esc(session()->get('id')) ?></div>
                </div>
                <a href="/logout" class="btn btn-danger btn-small">Déconnexion</a>
            </div>
        </div>
    </div>
</header>

<style>
    header {
        background: white;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }
    
    .logo {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }
    
    .header-right {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .user-info {
        text-align: right;
        font-size: 0.875rem;
    }
</style>
