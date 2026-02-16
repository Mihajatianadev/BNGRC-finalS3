<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body class="login-page">

    <div class="login-shell">
        <div class="login-card">
            <div class="login-grid">

                <div class="login-left">
                    <div class="login-left-inner">
                        <div class="login-left-media" style="background-image: url('');"></div>
                        <div class="login-left-overlay"></div>

                        <div class="login-brand">AMU</div>

                        <div class="login-back">
                            <a href="/accueil">Back to website <span aria-hidden="true">→</span></a>
                        </div>

                        <div class="login-left-content">
                            <div class="login-left-title">Capturing Moments,<br>Creating Memories</div>
                            <div class="login-dots" aria-hidden="true">
                                <div class="login-dot"></div>
                                <div class="login-dot"></div>
                                <div class="login-dot active"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login-right">
                    <h1>Connexion</h1>
                    <div class="login-sub">Tu n'as pas de compte ? <a href="#">Créer un compte</a></div>

                    <?php if (!empty($erreur_globale)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erreur_globale) ?></div>
                    <?php endif; ?>

                    <form class="login-form" method="post" action="/login" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input name="mot_de_passe" type="password" class="form-control" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="remember">
                            <label class="form-check-label" for="remember">I agree to the <a href="#">Terms &amp; Conditions</a></label>
                        </div>

                        <button class="btn btn-login-primary w-100" type="submit">Se connecter</button>
                    </form>

                    <div class="login-sep">Or register with</div>

                    <div class="row g-3">
                        <div class="col-6">
                            <button type="button" class="btn-social">
                                <span class="login-icon" aria-hidden="true"></span>
                                Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn-social">
                                <span class="login-icon" aria-hidden="true"></span>
                                Apple
                            </button>
                        </div>
                    </div>

                    <div class="login-footer-note">
                        Pour tester avec base.sql : admin@test.com / admin123
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
