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
</style>

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
