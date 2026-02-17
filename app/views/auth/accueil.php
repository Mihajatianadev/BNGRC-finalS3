<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BNGRC - Accueil</title>
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
            <div class="vitrine-brand-mark">
                  <img src="assets/images/bngrc.png" width="35" height="35" alt="BNGRC Logo" style="border-radius: 10px; object-fit: contain;">
            </div>
            <div class="vitrine-brand-text">BNGRC</div>
        </div>

        <nav class="vitrine-links" aria-label="Navigation principale">
            <a class="vitrine-link active" href="/accueil">Accueil</a>
            <a class="vitrine-link" href="/contacts">Contacts</a>
            <a class="vitrine-link" href="/actualites">Actualités</a>
        </nav>

        <div class="vitrine-actions">
            <a class="btn vitrine-btn" href="/login">Se connecter</a>
        </div>
    </div>
</header>

<main>
    <section class="vitrine-hero">
        <div class="vitrine-hero-media" style="background-image: url('assets/images/558306179_1339294591577261_426335191760356460_n.jpg');"></div>
        <div class="vitrine-hero-overlay"></div>

        <div class="container vitrine-hero-inner">
            <div class="vitrine-hero-content">
                <div class="vitrine-kicker" data-reveal>BUREAU NATIONAL DE GESTION DES RISQUES ET DES CATASTROPHES</div>
                <h1 class="vitrine-title" data-reveal>Prévenir, gérer et renforcer la résilience face aux risques.</h1>
                <p class="vitrine-sub" data-reveal>
                    Information, coordination et sensibilisation pour réduire les impacts des catastrophes.
                </p>

                <div class="vitrine-cta" data-reveal>
                    <a class="btn vitrine-btn-primary" href="#actualites">Voir les actualités</a>
                    <a class="btn vitrine-btn" href="/login">S'inscrire</a>
                </div>
            </div>
        </div>
    </section>

    <section class="vitrine-section" id="actualites">
        <div class="container">
            <div class="vitrine-section-head">
                <h2 class="vitrine-h2" data-reveal>Actualités</h2>
                <p class="vitrine-p" data-reveal>Les dernières informations et actions menées sur le terrain.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Mobilisation du secteur privé</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Mobilisation solidaire</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Bilan & interventions</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
                </div>
            </div>

            <div class="text-center mt-4" data-reveal>
                <a class="btn vitrine-btn" href="#">Toutes les actualités</a>
            </div>
        </div>
    </section>

    <section class="vitrine-section vitrine-section-alt" id="contacts">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h2 class="vitrine-h2" data-reveal>Contacts</h2>
                    <p class="vitrine-p" data-reveal>
                        Pour toute information, sensibilisation ou coordination, contactez le BNGRC.
                    </p>
                    <div class="vitrine-contact" data-reveal>
                        <div class="vitrine-contact-item">Téléphone: +261 34 05 480 68 / +261 34 05 480 69</div>
                        <div class="vitrine-contact-item">Adresse: Avaratra Antanimora Route Mausolé</div>
                            <div class="vitrine-contact-item">Suivez nous</div>
<div style="display: flex; gap: 10px; align-items: center;">
    <div class="vitrine-brand-mark">
        <img src="assets/bootstrap-icons/facebook.svg" width="35" height="35" alt="Facebook" style="border-radius: 10px; object-fit: contain;">
    </div>
    <div class="vitrine-brand-mark">
        <img src="assets/bootstrap-icons/whatsapp.svg" width="35" height="35" alt="WhatsApp" style="border-radius: 10px; object-fit: contain;">
    </div>
</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="vitrine-panel" data-reveal>
                        <div class="vitrine-panel-title">Urgences en cours</div>
                        <div class="vitrine-panel-text">Espace réservé (contenu à ajouter).</div>
                        <a class="btn vitrine-btn-primary" href="/login">Accéder à l'espace</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="vitrine-footer">
    <div class="container">
        <div class="vitrine-footer-inner">
            <div class="vitrine-footer-left"> <?= date('Y') ?> BNGRC</div>
            <div class="vitrine-footer-right">
                <a class="vitrine-footer-link" href="/login">Se connecter</a>
            </div>
        </div>
    </div>
</footer>

<script src="/js/accueil.js"></script>
</body>
</html>
