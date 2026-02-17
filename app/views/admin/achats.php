<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Liste des achats</title>
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
            <a class="admin2-nav-link <?= $actif('/admin/achats') ?>" href="/admin/achats" title="Achats">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/a-propos') ?>" href="/admin/a-propos" title="A propos">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.925 2 3 5.925 3 11s3.925 9 9 9 9-3.925 9-9-3.925-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/dashboard') ?>" href="/admin/dashboard" title="Distribuer dons">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20 7h-2.18A3 3 0 0 0 15 5H9a3 3 0 0 0-2.82 2H4a2 2 0 0 0-2 2v2h2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9h2V9a2 2 0 0 0-2-2zM9 7h6a1 1 0 0 1 1 1v1H8V8a1 1 0 0 1 1-1zm9 14H6v-9h12v9zm2-11H4V9h16v1z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/recapitulatif') ?>" href="/admin/recapitulatif" title="Récapitulatif">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/stock') ?>" href="/admin/stock" title="Stock">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M3 7l9-4 9 4-9 4-9-4zm2 6.2V17l7 3 7-3v-3.8l-7 3-7-3z"/></svg>
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
            <div class="admin2-title">Liste des achats de besoins</div>
        </div>

        <div class="admin2-content">
            <div class="admin2-card admin2-card-pad mb-4">
                <form method="get" class="row g-4 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label">Filtrer par ville</label>
                        <select name="ville" class="form-select">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= (int)$v['id_ville'] ?>" <?= ($id_ville === (int)$v['id_ville']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 d-grid">
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
                                <th>Date Achat</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th class="text-end">Montant Total (Ar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($achats)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucun achat trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($achats as $a): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($a['ville'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($a['date_achat']) ?></td>
                                        <td><?= htmlspecialchars($a['produit']) ?></td>
                                        <td><?= number_format($a['quantite_achetee'], 2, ',', ' ') ?> <?= htmlspecialchars($a['unite']) ?></td>
                                        <td class="text-end fw-bold"><?= number_format($a['montant_total'], 2, ',', ' ') ?></td>
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
