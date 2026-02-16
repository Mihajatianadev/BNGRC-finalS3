<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>
<body class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Connexion</h1>

            <?php if (!empty($erreur_globale)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erreur_globale) ?></div>
            <?php endif; ?>

            <form method="post" action="/login" novalidate>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input name="mot_de_passe" type="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="mt-3 text-muted small">
                Pour tester avec base.sql :
                admin@test.com / admin123
            </div>
        </div>
    </div>

</body>
</html>
