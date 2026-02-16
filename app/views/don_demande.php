<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la demande & Don</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4">🎁 Détail de la demande</h1>

    <?php if (!empty($demande)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Demande #<?= htmlspecialchars($demande['id_demande']) ?></h5>
                <p><strong>Ville :</strong> <?= htmlspecialchars($demande['ville'] ?? $demande['id_ville']) ?></p>
                <p><strong>Produit :</strong> <?= htmlspecialchars($demande['produit'] ?? $demande['id_produit']) ?></p>
                <p><strong>Quantité demandée :</strong> <?= htmlspecialchars($demande['quantite_demandee']) ?></p>
                <p><strong>Date de demande :</strong> <?= htmlspecialchars($demande['date_demande']) ?></p>
                <p><strong>Statut :</strong> <?= htmlspecialchars($demande['statut']) ?></p>
            </div>
        </div>

        <h2 class="mb-3">Faire un don pour cette demande</h2>
        <form action="/donner" method="POST" class="card p-4">
            <!-- ID de la demande -->
            <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">

            <!-- Sélection du produit (si besoin) -->
            <div class="mb-3">
                <label for="id_produit" class="form-label">Produit</label>
                <input type="text" class="form-control" id="id_produit" name="id_produit" 
                       value="<?= htmlspecialchars($demande['produit'] ?? $demande['id_produit']) ?>" readonly>
            </div>

            <!-- Quantité à donner -->
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité à donner</label>
                <input type="number" step="0.01" class="form-control" id="quantite" name="quantite" required>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-success">Donner</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Aucune demande trouvée.</div>
    <?php endif; ?>
</div>
</body>
</html>
