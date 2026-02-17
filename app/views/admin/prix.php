<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Dashboard Demandes</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>

 
<body class="admin2-page">

<div class="admin2-layout">
        <?php require __DIR__ . '/_navbar.php'; ?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$chemin_actuel = (string)($_SERVER['REQUEST_URI'] ?? '');
$actif = function ($prefix) use ($chemin_actuel) {
    return strpos($chemin_actuel, $prefix) === 0 ? 'active' : '';
};
?>    
<div class="container mt-4">
    <h3>Paramétrage des prix unitaires des produits</h3>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Unité</th>
                <th>Prix actuel</th>
                <th>Nouveau prix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['unite']) ?></td>
                    <td><?= number_format($p['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                    <td><?= number_format($p['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                    <td>
                        <form method="post" action="/admin/prix/update" class="d-flex">
                            <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                            <input type="number" step="0.01" name="prix_unitaire" class="form-control me-2" required>
                            <button class="btn btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
=<?php require __DIR__ . '/footer.php'; ?>
</div>
</div>
</body>
</html>
