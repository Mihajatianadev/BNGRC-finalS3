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

    <?php require __DIR__ . '/_navbar.php'; ?>

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
                    <div class="col-lg-3">
                        <label class="form-label">Date début</label>
                        <input type="date" class="form-control" name="date_debut" value="<?= htmlspecialchars($date_debut ?? '') ?>">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Date fin</label>
                        <input type="date" class="form-control" name="date_fin" value="<?= htmlspecialchars($date_fin ?? '') ?>">
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
                                <th class="text-end">Prix unitaire</th>
                                <th>Quantité</th>
                                <th class="text-end">Montant Total (Ar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($achats)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun achat trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($achats as $a): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($a['ville'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($a['date_achat']) ?></td>
                                        <td><?= htmlspecialchars($a['produit']) ?></td>
                                        <td class="text-end"><?= number_format((float)($a['prix_unitaire'] ?? 0), 2, ',', ' ') ?></td>
                                        <td><?= number_format((float)$a['quantite_achetee'], 2, ',', ' ') ?> <?= htmlspecialchars($a['unite']) ?></td>
                                        <td class="text-end fw-bold"><?= number_format((float)$a['montant_total'], 2, ',', ' ') ?></td>
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
