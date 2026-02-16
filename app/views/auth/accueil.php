<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Objets</title>
</head>
<body>



<div class="container">
<h1>Liste des objets</h1>

<?php foreach ($objets as $obj): ?>
<form action=>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">

        <?php if ($obj['lien_image']) : ?>
            <img src="/images/<?= $obj['lien_image'] ?>" width="150">
        <?php endif; ?>

        <h3><?= htmlspecialchars($obj['detail']) ?></h3>
        <p>Prix : <?= number_format($obj['prix_estimatif'], 0, ',', ' ') ?> Ar</p>
        <button>Echangé</button>
    </div>

<?php endforeach; ?>

</div>

</body>
</html>
