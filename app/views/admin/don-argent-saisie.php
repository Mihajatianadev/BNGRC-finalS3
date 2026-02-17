<?php
// On récupère les villes pour le select
$pdo = Flight::db();
$st = $pdo->query('SELECT id_ville, nom FROM villes ORDER BY nom');
$villes = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie Don Argent - BNGRC</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/demande.css">
</head>
<body class="admin2-page">

<div class="admin2-layout">
    <aside class="admin2-sidebar">
        <div class="admin2-brand" title="Admin">A</div>
        <nav class="admin2-nav">
            <a class="admin2-nav-link" href="/admin/voir-tout" title="Accueil">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 3 10v11h7v-7h4v7h7V10L12 3zm7 16h-3v-7H8v7H5v-8l7-5 7 5v8z"/></svg>
            </a>
            <a class="admin2-nav-link" href="/admin/a-propos" title="A propos">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.925 2 3 5.925 3 11s3.925 9 9 9 9-3.925 9-9-3.925-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/></svg>
            </a>
            <a class="admin2-nav-link" title="Demandes en attente" href="/admin/dashboard">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20 7h-2.18A3 3 0 0 0 15 5H9a3 3 0 0 0-2.82 2H4a2 2 0 0 0-2 2v2h2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9h2V9a2 2 0 0 0-2-2zM9 7h6a1 1 0 0 1 1 1v1H8V8a1 1 0 0 1 1-1zm9 14H6v-9h12v9zm2-11H4V9h16v1z"/></svg>
            </a>
            <a class="admin2-nav-link" title="Récapitulatif" href="/admin/recapitulatif">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
            </a>
        </nav>
    </aside>

    <main class="admin2-main">
        <div class="admin2-topbar">
            <div class="admin2-title">Saisir un Don en Argent</div>
        </div>

        <div class="admin2-content">
            <div class="admin2-card admin2-card-pad" style="max-width: 500px; margin: 0 auto;">
                <form action="/admin/don-argent" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ville bénéficiaire</label>
                        <select name="id_ville" class="form-select" required>
                            <option value="">-- Choisir une ville --</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= $v['id_ville'] ?>"><?= htmlspecialchars($v['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant (Ar)</label>
                        <input type="number" step="0.01" name="montant" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer le don</button>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
