<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Dashboard Demandes</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>
<body class="container py-3">

<?php require __DIR__ . '/_navbar.php'; ?>

<h1 class="h4 mb-3">Dashboard Admin - Demandes</h1>

<form method="get" class="row g-3 align-items-end mb-3">
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

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle bg-white">
        <thead class="table-dark">
            <tr>
                <th>Ville</th>
                <th class="text-end">Nombre de produits demandés</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($demandes)): ?>
                <tr>
                    <td colspan="2" class="text-center text-muted py-4">Aucune demande trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($demande['ville']) ?></td>
                        <td class="text-end fw-bold"><?= (int)$demande['nombre_produits'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
