<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <p><?= esc($error) ?></p>
    <?php endif; ?>

    <?php if (isset($validation)): ?>
        <ul>
            <?php if ($validation->hasError('mail')): ?>
                <li><?= esc($validation->getError('mail')) ?></li>
            <?php endif; ?>
            <?php if ($validation->hasError('password')): ?>
                <li><?= esc($validation->getError('password')) ?></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <form action="/login" method="post">
        <label for="mail">Email:</label>
        <input type="email" name="mail" id="mail" placeholder="abc@example.net" value="<?= esc($mail ?? '') ?>" required>
        <br>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" placeholder="********" required>
        <input type="submit" value="Se connecter">
    </form>
    <p>Pas encore inscrit? <a href="/inscription">Inscrivez-vous ici!</a></p>

</body>
</html>