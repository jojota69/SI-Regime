<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Requis pour la version mobile CSS -->
    <title>SI-Regime - Création de compte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h1>Créez votre compte</h1>
        <form action="inscription_sante.php" method="POST" id="registerForm">
            <fieldset>
                <legend>Informations personnelles</legend>
                
                <div class="input-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex: Jean Dupont">
                </div>

                <div class="input-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" required placeholder="jean.dupont@email.com">
                </div>

                <div class="input-group">
                    <label for="genre">Genre</label>
                    <select id="genre" name="genre" required>
                        <option value="">Sélectionnez votre genre</option>
                        <option value="F">Femme</option>
                        <option value="H">Homme</option>
                        <option value="A">Autre</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend>Sécurité</legend>
                
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <button type="button" id="togglePassword" class="btn-toggle">Afficher</button>
                    </div>
                </div>
            </fieldset>
            <button type="submit" class="btn-primary">Suivant : Mes infos de santé</button>
            
        </form>
        <div class="auth-switch">
            <p>Vous avez deja un compte ? <a href="login.php">Se connecter</a></p>
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