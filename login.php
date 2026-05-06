<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-Regime - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h1>Bon retour parmi nous !</h1>
        <form action="dashboard.php" method="POST" id="loginForm">
            <fieldset>
                <legend>Identifiants de connexion</legend>
                
                <div class="input-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" required placeholder="votre.email@exemple.com">
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>

                        <button type="button" id="togglePassword" class="btn-toggle">Afficher</button>
                    </div>
                </div>
            </fieldset>
            <button type="submit" class="btn-primary">Se connecter à mon compte</button>
        </form>
        <div class="auth-switch">
            <p>Pas encore de compte ? <a href="inscription_user.php">Creer un compte</a></p>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Afficher' : 'Masquer';
        });
    </script>
</body>
</html>