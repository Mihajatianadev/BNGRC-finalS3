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

    <?php require __DIR__ . '/_navbar.php'; ?>

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
