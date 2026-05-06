<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-Regime - Profil Santé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h1>Votre profil santé</h1>
        
        <!-- Le formulaire envoie vers le tableau de bord final -->
        <form action="dashboard.php" method="POST" id="healthForm">
            
            <fieldset>
                <legend>Pour calculer votre IMC</legend>
                
                <div class="input-group">
                    <label for="taille">Taille (en cm)</label>
                    <input type="number" id="taille" name="taille" required placeholder="Ex: 175" min="50" max="250">
                </div>

                <div class="input-group">
                    <label for="poids">Poids (en kg)</label>
                    <!-- step="0.1" permet d'entrer des décimales comme 65.5 -->
                    <input type="number" id="poids" name="poids" required placeholder="Ex: 68.5" min="20" max="300" step="0.1">
                </div>
            </fieldset>

            <button type="submit" class="btn-primary">Calculer mon IMC et terminer</button>
            
        </form>

        <div class="auth-switch">
            <p><a href="inscription_user.php">← Retour à l'étape précédente</a></p>
        </div>
    </div>

</body>
</html>