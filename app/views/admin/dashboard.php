<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Dashboard Demandes</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body class="admin2-page">

<?php
$chemin_actuel = (string)($_SERVER['REQUEST_URI'] ?? '');
$actif = function ($prefix) use ($chemin_actuel) {
    return strpos($chemin_actuel, $prefix) === 0 ? 'active' : '';
};
?>

<div class="admin2-layout">

    <aside class="admin2-sidebar">
        <div class="admin2-brand" title="Admin">A</div>

        <nav class="admin2-nav">
            <a class="admin2-nav-link <?= $actif('/admin/voir-tout') ?>" href="/admin/voir-tout" title="Accueil">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 3 10v11h7v-7h4v7h7V10L12 3zm7 16h-3v-7H8v7H5v-8l7-5 7 5v8z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/a-propos') ?>" href="/admin/a-propos" title="A propos">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.925 2 3 5.925 3 11s3.925 9 9 9 9-3.925 9-9-3.925-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/dashboard') ?>" title="Demandes en attente" href="/admin/dashboard">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20 7h-2.18A3 3 0 0 0 15 5H9a3 3 0 0 0-2.82 2H4a2 2 0 0 0-2 2v2h2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9h2V9a2 2 0 0 0-2-2zM9 7h6a1 1 0 0 1 1 1v1H8V8a1 1 0 0 1 1-1zm9 14H6v-9h12v9zm2-11H4V9h16v1z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/don-argent') ?>" title="Don Argent" href="/admin/don-argent">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/recapitulatif') ?>" title="Récapitulatif" href="/admin/recapitulatif">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
            </a>
        </nav>

        <div class="admin2-sidebar-footer">
            <a class="admin2-logout" href="/logout" title="Déconnexion">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M16 13v-2H7V8l-5 4 5 4v-3h9zm4-10H10a2 2 0 0 0-2 2v4h2V5h10v14H10v-4H8v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/></svg>
            </a>
        </div>
    </aside>

    <main class="admin2-main">

        <div class="admin2-topbar">
            <div class="admin2-title">Dashboard demandes</div>
            <div class="admin2-search">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0 0 10 2zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12z"/></svg>
                <input type="text" class="form-control" placeholder="rechercher" aria-label="rechercher">
            </div>
        </div>

        <div class="admin2-content">

            <div class="admin2-card admin2-card-pad mb-4">
                <form method="get" class="row g-4 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Région</label>
                        <select name="region" class="form-select">
                            <option value="">Toutes les régions</option>
                            <?php foreach ($regions as $region): ?>
                                <option value="<?= (int)$region['id_region'] ?>" <?= ($id_region === (int)$region['id_region']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($region['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Ville</label>
                        <select name="ville" class="form-select" <?= $id_region ? '' : 'disabled' ?>>
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= (int)$ville['id_ville'] ?>" <?= ($id_ville === (int)$ville['id_ville']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!$id_region): ?>
                            <div class="form-text">Choisis d'abord une région pour activer le filtre ville.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>

            <div class="admin2-card">
                <div class="admin2-table-wrap">
                    <table class="table admin2-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th class="text-end">Nombre de produits demandés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($demandes)): ?>
                                <tr>
                                    <td colspan="2" class="text-center admin2-muted py-4">Aucune demande trouvée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($demandes as $demande): ?>
                                    <tr>
                                        <td class="fw-bold">
                                            <a class="text-decoration-none" href="/demande/<?= urlencode((string)$demande['id_demande']) ?>">
                                                <?= htmlspecialchars($demande['ville']) ?>
                                            </a>
                                        </td>
                                        <td class="text-end fw-bold">
                                            <a class="text-decoration-none" href="/demande/<?= urlencode((string)$demande['id_demande']) ?>">
                                                <?= (int)$demande['nombre_produits'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</div>

</body>
</html>
