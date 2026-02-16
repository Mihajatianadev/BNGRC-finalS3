<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body class="login2-page">

    <div class="login2-shell">
        <div class="login2-card">
            <div class="login2-grid">

                <div class="login2-left">
                 <div class="login2-brand-mark">
    <img src="assets/images/bngrc.png" width="35" height="35" alt="BNGRC Logo" style="border-radius: 10px; object-fit: contain;">
</div>
<div class="login2-brand-name">BNGRC</div>

                    <div class="login2-title">Connexion en tant qu'admin</div>
                    <div class="login2-desc">Connectez-vous pour suivre les dons </div>

                    <?php if (!empty($erreur_globale)): ?>
                        <div class="alert alert-danger mt-3"><?= htmlspecialchars($erreur_globale) ?></div>
                    <?php endif; ?>

                    <form class="login2-form" method="post" action="/login" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group login2-input">
                                <span class="input-group-text">
                                    <span class="login2-ico"><img src="assets/bootstrap-icons/envelope-at.svg"></span>
                                </span>
                                <input name="email" type="email" class="form-control" placeholder="Entrer votre email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Mot de passe</label>
                            <div class="input-group login2-input">
                                <span class="input-group-text">
                                    <span class="login2-ico" aria-hidden="true"><img src="assets/bootstrap-icons/lock.svg"></span>
                                </span>
                                <input name="mot_de_passe" type="password" class="form-control" placeholder="Entrer votre mot de passe" required>

                            </div>
                        </div>


                        <button class="btn login2-btn w-100" type="submit">Se connecter</button>
                    </form>

                    <div class="login2-signup">
                        Pas de compte ?
                        <a class="login2-link" href="#">S'inscrire</a>
                    </div>

                    <div class="login2-note">Pour tester avec base.sql : admin@test.com / admin123</div>
                </div>

                <div class="login2-right">
                    <div class="login2-right-inner">
                        <div class="login2-right-title">“Izay tsara fiomanana tsy ho tampohin'ny Loza”</div>
                        <div class="login2-profile-name">"Ceux qui savent se preparer seront à l'abri du danger"<br></div>
                          <div class="login2-note"></div>
                        
                        <div class="login2-profile">
                            <div class="login2-avatar" style="background-image: url('assets/images/WhatsApp-Image-2025-11-17-a-09.07.47_a717b263.jpg');"></div>
                            <div>
                                <div class="login2-profile-name">RAMANANTSOA Gabriel </div>
                                <div class="login2-profile-role">Directeur Général</div>
                            </div>
                        </div>

                        <div class="login2-divider"></div>

                        <div class="login2-logos-title">JOIN 1K TEAMS</div>
                        <div class="login2-logos">
                            <div class="login2-logo">Discord</div>
                            <div class="login2-logo">mailchimp</div>
                            <div class="login2-logo">grammarly</div>
                            <div class="login2-logo">attentive</div>
                            <div class="login2-logo">HELLOSIGN</div>
                            <div class="login2-logo">INTERCOM</div>
                            <div class="login2-logo">Square</div>
                            <div class="login2-logo">Dropbox</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
