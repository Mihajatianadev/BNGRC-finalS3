<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BNGRC - Actualités</title>
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
            <a class="vitrine-link" href="/contacts">Contacts</a>
            <a class="vitrine-link active" href="/actualites">Actualités</a>
        </nav>

        <div class="vitrine-actions">
            <a class="btn vitrine-btn" href="/login">Se connecter</a>
        </div>
    </div>
</header>

<main>
    <section class="vitrine-section" id="actualites">
        <div class="container">
            <div class="vitrine-section-head">
                <h1 class="vitrine-h2" data-reveal>Actualités</h1>
                <p class="vitrine-p" data-reveal>Suivez les dernières nouvelles et interventions du BNGRC. (Images à ajouter)</p>
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

            <div class="row g-4 mt-1">
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Sensibilisation face aux aléas</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Urgences en cours</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="vitrine-card" data-reveal>
                        <div class="vitrine-card-img" style="background-image: url('');"></div>
                        <div class="vitrine-card-body">
                            <div class="vitrine-card-title">Communiqués</div>
                            <div class="vitrine-card-text">Résumé de l'action (image à ajouter).</div>
                        </div>
                    </article>
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
