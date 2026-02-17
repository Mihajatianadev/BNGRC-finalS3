<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Stock</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>
<body class="admin2-page">

<div class="admin2-layout">
    <?php require __DIR__ . '/_navbar.php'; ?>

    <main class="admin2-main">
        <div class="admin2-topbar">
            <div class="admin2-title">Stock</div>
        </div>

        <div class="admin2-content">
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erreur ?? '') ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success ?? '') ?></div>
            <?php endif; ?>

            <!-- Tableau des stocks -->
            <div class="admin2-card">
                <div class="admin2-table-wrap">
                    <table class="table admin2-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Produit</th>
                                <th>Unité</th>
                                <th class="text-end">Quantité disponible</th>
                                <th class="text-end">Prix unitaire</th>
                                <th class="text-end">Remise (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stocks)): ?>
                                <tr>
                                    <td colspan="6" class="text-center admin2-muted py-4">Aucun stock.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($stocks as $s): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($s['categorie'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($s['produit'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($s['unite'] ?? '') ?></td>
                                        <td class="text-end"><?= htmlspecialchars($s['quantite_disponible'] ?? '0') ?></td>
                                        <td class="text-end"><?= number_format((float)($s['prix_unitaire'] ?? 0), 2, ',', ' ') ?> Ar</td>
                                        <td class="text-end"><?= htmlspecialchars($s['remise_pct'] ?? '0') ?> %</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Formulaire de vente -->
            <div class="admin2-card admin2-card-pad mt-4">
                <h5>Vente de produits</h5>
                <form method="post" action="/admin/vente" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Produit</label>
                        <select name="produit_id" id="produitSelect" class="form-select">
                            <?php foreach ($stocks as $s): ?>
                                <option value="<?= $s['id_produit'] ?? 0 ?>" 
                                        data-remise="<?= $s['remise_pct'] ?? 0 ?>">
                                    <?= htmlspecialchars($s['produit'] ?? '') ?> 
                                    (Stock: <?= $s['quantite_disponible'] ?? 0 ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="quantite" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Remise appliquée</label>
                        <div id="remiseAffichee" class="form-control-plaintext">0 %</div>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-success">Vendre</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('produitSelect').addEventListener('change', function() {
    let remise = this.options[this.selectedIndex].getAttribute('data-remise');
    document.getElementById('remiseAffichee').textContent = remise + " %";
});
</script>

</body>
</html>
