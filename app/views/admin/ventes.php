<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Ventes</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>
<body class="admin2-page">

<div class="admin2-layout">

    <aside class="admin2-sidebar">
        <div class="admin2-brand" title="Admin">A</div>
        <nav class="admin2-nav">
            <a class="admin2-nav-link" href="/admin/stock" title="Stock">Stock</a>
            <a class="admin2-nav-link active" href="/admin/ventes" title="Ventes">Ventes</a>
        </nav>
    </aside>

    <main class="admin2-main">
        <div class="admin2-topbar">
            <div class="admin2-title">Historique des ventes</div>
        </div>

        <div class="admin2-content">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="admin2-card">
                <div class="admin2-table-wrap">
                    <table class="table admin2-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Remise (%)</th>
                                <th>Prix final</th>
                                <th>Date de vente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ventes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center admin2-muted py-4">Aucune vente enregistrée.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ventes as $v): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($v['produit']) ?></td>
                                        <td><?= htmlspecialchars($v['quantite']) ?></td>
                                        <td><?= number_format($v['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                        <td><?= htmlspecialchars($v['remise_pct']) ?> %</td>
                                        <td><?= number_format($v['prix_final'], 2, ',', ' ') ?> Ar</td>
                                        <td><?= htmlspecialchars($v['date_vente']) ?></td>
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
