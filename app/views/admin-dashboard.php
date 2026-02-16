<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Dashboard Demandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

    <h1 class="mb-4">Dashboard Admin - Demandes</h1>

    <form method="get" class="row g-3 align-items-end mb-4">
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

    <?php if (empty($demandes)): ?>
        <div class="alert alert-info">Aucune demande trouvée.</div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($demandes as $demande): ?>
                <a href="/demande/<?= urlencode($demande['id_demande']) ?>" class="text-decoration-none">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">
                                    <?= htmlspecialchars($demande['ville']) ?>
                            </div>
                            <div class="text-muted small">
                                Région: <?= htmlspecialchars($demande['region']) ?>
                                <?php if (!empty($demande['date_demande'])): ?>
                                    | Date: <?= htmlspecialchars($demande['date_demande']) ?>
                                <?php endif; ?>
                                <?php if (!empty($demande['statut'])): ?>
                                    | Statut: <?= htmlspecialchars($demande['statut']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="fs-4 fw-bold">
                            <?= (int)$demande['nombre_produits'] ?>
                        </div>
                    </div>
                </div>
                </a>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
