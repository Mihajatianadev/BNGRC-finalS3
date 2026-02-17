<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BNGRC - Contacts</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/accueil.css">
</head>
<body class="vitrine-page">

<div class="vitrine-loader" id="vitrineLoader" aria-hidden="true">
    <div class="vitrine-loader-card">
        <div class="vitrine-loader-mark"></div>
        <div class="vitrine-loader-bar">
            <div class="vitrine-loader-progress"></div>
        </div>
    </div>
</div>

<header class="vitrine-header">
    <div class="container-fluid vitrine-nav">
        <div class="vitrine-brand">
            <div class="vitrine-brand-mark"></div>
            <div class="vitrine-brand-text">BNGRC</div>
        </div>

        <nav class="vitrine-links" aria-label="Navigation principale">
            <a class="vitrine-link" href="/accueil">Accueil</a>
            <a class="vitrine-link active" href="/contacts">Contacts</a>
            <a class="vitrine-link" href="/actualites">Actualités</a>
        </nav>

        <div class="vitrine-actions">
            <a class="btn vitrine-btn" href="/login">Se connecter</a>
        </div>
    </div>
</header>

<main>
    <section class="vitrine-section vitrine-section-alt" id="contacts">
        <div class="container">
            <div class="vitrine-section-head">
                <h1 class="vitrine-h2" data-reveal>Contacts</h1>
                <p class="vitrine-p" data-reveal>Besoin d'informations ou de coordination ? Contactez le BNGRC. (À compléter)</p>
            </div>

            <div class="row g-4 align-items-start">
                <div class="col-lg-6">
                    <div class="vitrine-panel" data-reveal>
                        <div class="vitrine-panel-title">Coordonnées</div>
                        <div class="vitrine-contact" style="margin-top: 10px;">
                            <div class="vitrine-contact-item">Téléphone: +261 34 05 480 68 / +261 34 05 480 69</div>
                            <div class="vitrine-contact-item">Adresse: Avaratra Antanimora Route Mausolé</div>
                            <div class="vitrine-contact-item">Horaires: 6/7j de 08hOO à 15h00</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="vitrine-panel" data-reveal>
                        <div class="vitrine-panel-title">Localisation</div>
                        <div class="vitrine-panel-text">Avaratra Antanimora Route Mausolé.</div>
                        <div class="vitrine-card-img" style="height: 220px; border-radius: 16px; background-image: url('assets/images/558306179_1339294591577261_426335191760356460_n.jpg');"></div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4" data-reveal>
                <a class="btn vitrine-btn-primary" href="/login">Accéder à l'espace</a>
            </div>
        </div>
    </section>
</main>

<footer class="vitrine-footer">
    <div class="container">
        <div class="vitrine-footer-inner">
            <div class="vitrine-footer-left">© <?php echo date('Y'); ?> BNGRC</div>
            <div class="vitrine-footer-right">
                <a class="vitrine-footer-link" href="/login">Se connecter</a>
            </div>
        </div>
    </div>
</footer>

<script src="/js/accueil.js"></script>
</body>
</html>
