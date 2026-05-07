<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        .hidden { display: none; }
        .error-msg { color: red; font-size: 13px; margin-bottom: 10px; }
        .container { max-width: 400px; margin: 50px auto; font-family: sans-serif; }
        input, select { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
    </style>
</head>
<body>
    <div class="container">
        <div id="box-etape1">
            <h2>Étape 1 : Identité</h2>
            <form id="formEtape1">
                <?= csrf_field() ?>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="genre" required>
                    <option value="">Sélectionnez votre genre</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <div id="err1" class="error-msg"></div>
                <button type="submit">Suivant</button>
            </form>
        </div>

        <div id="box-etape2" class="hidden">
            <h2>Étape 2 : Santé</h2>
            <form id="formEtape2">
                <?= csrf_field() ?>
                <input type="number" step="0.01" name="taille" placeholder="Taille (en cm, ex: 175)" required>
                <input type="number" step="0.01" name="poids" placeholder="Poids (en kg, ex: 70)" required>
                <div id="err2" class="error-msg"></div>
                <button type="button" onclick="basculer(1)">Retour</button>
                <button type="submit">Finaliser mon profil</button>
            </form>
        </div>

        <div id="box-success" class="hidden">
            <h2 style="color: green;">Félicitations !</h2>
            <p>Votre profil est complété et votre IMC a été calculé.</p>
            <a href="<?= base_url('/') ?>">Se connecter maintenant</a>
        </div>
    </div>

    <script>
        const urlBase = "<?= base_url() ?>";

        function basculer(etape) {
            document.getElementById('box-etape1').classList.toggle('hidden', etape !== 1);
            document.getElementById('box-etape2').classList.toggle('hidden', etape !== 2);
        }

        document.getElementById('formEtape1').onsubmit = async (e) => {
            e.preventDefault();
            const res = await fetch(urlBase + '/user/validerEtape1', {
                method: 'POST',
                body: new FormData(e.target),
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const json = await res.json();
            if(json.success) {
                document.getElementById('err1').innerText = "";
                basculer(2);
            } else {
                document.getElementById('err1').innerText = Object.values(json.errors).join(" | ");
            }
        };

        document.getElementById('formEtape2').onsubmit = async (e) => {
            e.preventDefault();
            const res = await fetch(urlBase + '/user/finaliser', {
                method: 'POST',
                body: new FormData(e.target),
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            const json = await res.json();
            if(json.success) {
                document.getElementById('box-etape2').classList.add('hidden');
                document.getElementById('box-success').classList.remove('hidden');
            } else {
                document.getElementById('err2').innerText = Object.values(json.errors).join(" | ");
            }
        };
    </script>
</body>
</html>