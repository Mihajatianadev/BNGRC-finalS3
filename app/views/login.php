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

<div class="login2-title">Distribution des dons du BNGRC</div>
<div class="login2-desc">
Le BNGRC organise, coordonne et optimise la distribution des dons afin de répondre rapidement aux besoins des populations touchées par les catastrophes. 
Grâce à une gestion efficace des ressources, chaque contribution est orientée vers les zones les plus urgentes pour garantir une aide équitable, rapide et adaptée sur le terrain.
</div>


                    <?php if (!empty($erreur_globale)): ?>
                        <div class="alert alert-danger mt-3"><?= htmlspecialchars($erreur_globale) ?></div>
                    <?php endif; ?>

                    <form class="login2-form" method="post" action="/login" novalidate>
                        <input type="hidden" name="email" value="admin@test.com">
                        <input type="hidden" name="mot_de_passe" value="admin123">

                        <button class="btn login2-btn w-100" type="submit">COMMENCER</button>
                    </form>


                      <div class="login2-signup">
                        <a class="login2-link" href="/accueil">Retour à l'accueil</a>
                    </div>

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
<div class="login2-logos-title">Actions Realisable</div>

<div class="login2-logos">
    <div class="login2-logo">BNGRC</div>
    <div class="login2-logo">Distribution Dons</div>
    <div class="login2-logo">Insertion des Besoins</div>
    <div class="login2-logo">Tableau de Bord des Dons et Besoins</div>
    <div class="login2-logo">Gestion Stock</div>
    <div class="login2-logo">Achat Don</div>
    <div class="login2-logo">Paramétrage des prix unitaires des produits</div>
</div>

                </div>

            </div>
        </div>
    </div>

</body>
</html>
