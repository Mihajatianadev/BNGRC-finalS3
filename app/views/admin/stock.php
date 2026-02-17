<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Stock</title>
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
            <div class="admin2-title">Stock</div>
            <div class="admin2-search">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0 0 10 2zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12z"/></svg>
                <input type="text" class="form-control" placeholder="rechercher" aria-label="rechercher">
            </div>
        </div>

        <div class="admin2-content">

            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="admin2-card">
                <div class="admin2-table-wrap">
                    <table class="table admin2-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Produit</th>
                                <th>Unité</th>
                                <th class="text-end">Quantité disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stocks)): ?>
                                <tr>
                                    <td colspan="4" class="text-center admin2-muted py-4">Aucun stock.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($stocks as $s): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($s['categorie']) ?></td>
                                        <td><?= htmlspecialchars($s['produit']) ?></td>
                                        <td><?= htmlspecialchars($s['unite']) ?></td>
                                        <td class="text-end fw-bold"><?= htmlspecialchars($s['quantite_disponible']) ?></td>
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
