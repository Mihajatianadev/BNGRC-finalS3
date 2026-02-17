<?php
// On récupère les villes pour le select
$pdo = Flight::db();
$st = $pdo->query('SELECT id_ville, nom FROM villes ORDER BY nom');
$villes = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie Don Argent - BNGRC</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/demande.css">
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
            <div class="admin2-title">Saisir un Don en Argent</div>
        </div>

        <div class="admin2-content">
            <div class="admin2-card admin2-card-pad" style="max-width: 500px; margin: 0 auto;">
                <form action="/admin/don-argent" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ville bénéficiaire</label>
                        <select name="id_ville" class="form-select" required>
                            <option value="">-- Choisir une ville --</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= $v['id_ville'] ?>"><?= htmlspecialchars($v['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant (Ar)</label>
                        <input type="number" step="0.01" name="montant" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer le don</button>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
