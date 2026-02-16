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
                    <div class="login2-brand">
                        <div class="login2-brand-mark"></div>
                        <div class="login2-brand-name">SoftQA</div>
                    </div>

                    <div class="login2-title">Welcome Back!</div>
                    <div class="login2-desc">Sign in to access your dashboard and continue optimizing your QA process.</div>

                    <?php if (!empty($erreur_globale)): ?>
                        <div class="alert alert-danger mt-3"><?= htmlspecialchars($erreur_globale) ?></div>
                    <?php endif; ?>

                    <form class="login2-form" method="post" action="/login" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group login2-input">
                                <span class="input-group-text">
                                    <span class="login2-ico" aria-hidden="true"></span>
                                </span>
                                <input name="email" type="email" class="form-control" placeholder="Enter your email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <div class="input-group login2-input">
                                <span class="input-group-text">
                                    <span class="login2-ico" aria-hidden="true"></span>
                                </span>
                                <input name="mot_de_passe" type="password" class="form-control" placeholder="Enter your password" required>
                                <span class="input-group-text">
                                    <span class="login2-ico" aria-hidden="true"></span>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <a class="login2-link" href="#">Forgot Password?</a>
                        </div>

                        <button class="btn login2-btn w-100" type="submit">Sign In</button>
                    </form>

                    <div class="login2-or"><span>OR</span></div>

                    <div class="d-grid gap-2">
                        <button type="button" class="login2-social">
                            <span class="login2-social-ico" aria-hidden="true"></span>
                            Continue with Google
                        </button>
                        <button type="button" class="login2-social">
                            <span class="login2-social-ico" aria-hidden="true"></span>
                            Continue with Apple
                        </button>
                    </div>

                    <div class="login2-signup">
                        Don't have an Account?
                        <a class="login2-link" href="#">Sign Up</a>
                    </div>

                    <div class="login2-note">Pour tester avec base.sql : admin@test.com / admin123</div>
                </div>

                <div class="login2-right">
                    <div class="login2-right-inner">
                        <div class="login2-right-title">Revolutionize QA with<br>Smarter Automation</div>
                        <div class="login2-quote">“SoftQA has completely transformed our testing process. It’s reliable, efficient, and ensures our releases are always top-notch.”</div>

                        <div class="login2-profile">
                            <div class="login2-avatar" style="background-image: url('');"></div>
                            <div>
                                <div class="login2-profile-name">Michael Carter</div>
                                <div class="login2-profile-role">Software Engineer at DevCore</div>
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
