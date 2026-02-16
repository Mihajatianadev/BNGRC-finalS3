<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Distribuer dons</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
    <style>
        .cellule-tronquee {
            max-width: 420px;
        }
    </style>
</head>
<body class="container-fluid py-3">

<div class="container">
    <?php require __DIR__ . '/_navbar.php'; ?>
</div>

<div class="container-fluid">

    <div class="d-flex flex-wrap gap-3 align-items-end mb-3">
        <form method="get" class="row g-3 align-items-end w-100">

            <div class="col-lg-3">
                <label class="form-label">Ville</label>
                <select name="ville" class="form-select">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= (int)$ville['id_ville'] ?>" <?= ($id_ville === (int)$ville['id_ville']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ville['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Date début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut ?? '') ?>">
            </div>

            <div class="col-lg-3">
                <label class="form-label">Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin ?? '') ?>">
            </div>

            <div class="col-lg-3 d-grid">
                <button class="btn btn-primary">Filtrer</button>
            </div>

        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle bg-white">
            <thead class="table-dark">
                <tr>
                    <th style="width: 14%">Ville</th>
                    <th style="width: 16%">Date demande</th>
                    <th style="width: 12%">Statut</th>
                    <th style="width: 29%">Besoins (produits / quantités)</th>
                    <th style="width: 29%">Dons reçus / distribué (produits, quantités, dates)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lignes)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Aucune demande trouvée.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lignes as $ligne): ?>
                        <?php
                            $besoins = (string)($ligne['besoins'] ?? '');
                            $distribue = (string)($ligne['distribue'] ?? '');
                        ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($ligne['ville']) ?></td>
                            <td><?= htmlspecialchars($ligne['date_demande']) ?></td>
                            <td><?= htmlspecialchars($ligne['statut']) ?></td>
                            <td class="cellule-tronquee text-truncate" title="<?= htmlspecialchars($besoins) ?>">
                                <?= htmlspecialchars($besoins) ?>
                            </td>
                            <td class="cellule-tronquee text-truncate" title="<?= htmlspecialchars($distribue) ?>">
                                <?= htmlspecialchars($distribue) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-muted small">
            Astuce: survole une cellule "Besoins" ou "Distribué" pour voir le contenu complet.
        </div>
    </div>

</div>

</body>
</html>
