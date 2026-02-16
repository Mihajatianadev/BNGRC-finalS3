<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la demande & Don</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>
<body class="bg-light">
<div class="container mt-4">

    <?php require __DIR__ . '/_navbar.php'; ?>

    <h1 class="mb-4">Détail de la demande</h1>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (!empty($demande)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Demande #<?= htmlspecialchars($demande['id_demande']) ?></h5>
                <p><strong>Ville :</strong> <?= htmlspecialchars($demande['ville'] ?? $demande['id_ville']) ?></p>
                <p><strong>Produit :</strong> <?= htmlspecialchars($demande['produit'] ?? $demande['id_produit']) ?></p>
                <p><strong>Quantité demandée :</strong> <?= htmlspecialchars($demande['quantite_demandee']) ?> <?= htmlspecialchars($demande['unite'] ?? '') ?></p>
                <p><strong>Date de demande :</strong> <?= htmlspecialchars($demande['date_demande']) ?></p>
                <p><strong>Statut :</strong> <?= htmlspecialchars($demande['statut']) ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Dons reçus / distributions déjà effectuées</h5>
                <?php if (empty($distributions)): ?>
                    <div class="text-muted">Aucune distribution enregistrée pour cette demande.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-end">Quantité envoyée</th>
                                    <th>Date de réception</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distributions as $dist): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($dist['produit'] ?? '') ?></td>
                                        <td class="text-end"><?= htmlspecialchars($dist['quantite_envoyee'] ?? '') ?> <?= htmlspecialchars($dist['unite'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($dist['date_distribution'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="mb-3">Faire un don pour cette demande</h2>
        <form action="/admin/donner" method="POST" class="card p-4">
            <!-- ID de la demande -->
            <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">

            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select id="select_categorie" name="id_categorie" class="form-select">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id_categorie'] ?>" <?= ((int)$demande['id_categorie'] === (int)$cat['id_categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Tu peux choisir n'importe quelle catégorie.</div>
            </div>

            <!-- Sélection du produit -->
            <div class="mb-3">
                <label class="form-label">Produit</label>
                <select id="select_produit" name="id_produit" class="form-select">
                    <?php foreach ($produits as $p): ?>
                        <option value="<?= (int)$p['id_produit'] ?>" <?= ((int)$demande['id_produit'] === (int)$p['id_produit']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nom']) ?> (<?= htmlspecialchars($p['unite']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Le don est libre (produit au choix), mais limité par le stock disponible.</div>
            </div>

            <div class="mb-3">
                <div class="alert alert-secondary mb-0" id="apercu_stock">
                    Stock disponible: <strong id="stock_valeur">-</strong>
                </div>
            </div>

            <!-- Quantité à donner -->
            <div class="mb-3">
                <label class="form-label">Quantité à donner</label>
                <input id="input_quantite" type="number" step="0.01" class="form-control" name="quantite" required>
                <div class="form-text" id="texte_validation"></div>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-success">Donner</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Aucune demande trouvée.</div>
    <?php endif; ?>
</div>

<script>
    (function() {
        const selectCategorie = document.getElementById('select_categorie');
        const selectProduit = document.getElementById('select_produit');
        const stockValeur = document.getElementById('stock_valeur');
        const inputQuantite = document.getElementById('input_quantite');
        const texteValidation = document.getElementById('texte_validation');
        const apercuStock = document.getElementById('apercu_stock');

        let stockActuel = null;

        async function chargerProduits() {
            const idCategorie = selectCategorie.value;
            selectProduit.innerHTML = '';

            const res = await fetch('/api/produits?categorie=' + encodeURIComponent(idCategorie));
            const produits = await res.json();

            for (const p of produits) {
                const opt = document.createElement('option');
                opt.value = p.id_produit;
                opt.textContent = p.nom + ' (' + p.unite + ')';
                selectProduit.appendChild(opt);
            }

            await chargerStock();
        }

        async function chargerStock() {
            const idProduit = selectProduit.value;
            if (!idProduit) {
                stockActuel = null;
                stockValeur.textContent = '-';
                return;
            }

            const res = await fetch('/api/stock/' + encodeURIComponent(idProduit));
            const data = await res.json();
            stockActuel = Number(data.quantite_disponible);
            stockValeur.textContent = isNaN(stockActuel) ? '-' : stockActuel;
            validerQuantite();
        }

        function validerQuantite() {
            if (stockActuel === null || isNaN(stockActuel)) {
                texteValidation.textContent = '';
                apercuStock.classList.remove('alert-danger');
                apercuStock.classList.add('alert-secondary');
                return;
            }

            const q = Number(inputQuantite.value);
            if (!inputQuantite.value) {
                texteValidation.textContent = '';
                apercuStock.classList.remove('alert-danger');
                apercuStock.classList.add('alert-secondary');
                return;
            }

            if (q > stockActuel) {
                texteValidation.textContent = 'Quantité trop grande par rapport au stock.';
                apercuStock.classList.add('alert-danger');
                apercuStock.classList.remove('alert-secondary');
            } else {
                texteValidation.textContent = '';
                apercuStock.classList.remove('alert-danger');
                apercuStock.classList.add('alert-secondary');
            }
        }

        selectCategorie.addEventListener('change', chargerProduits);
        selectProduit.addEventListener('change', chargerStock);
        inputQuantite.addEventListener('input', validerQuantite);

        // init
        if (selectProduit.value) {
            chargerStock();
        }
    })();
</script>
</body>
</html>
