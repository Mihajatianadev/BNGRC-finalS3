<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la demande & Don</title>
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
            <div class="admin2-title">Détail de la demande</div>
            <div class="admin2-search">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0 0 10 2zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12z"/></svg>
                <input type="text" class="form-control" placeholder="rechercher" aria-label="rechercher">
            </div>
        </div>

        <div class="admin2-content">

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if (!empty($demande)): ?>
        <div class="admin2-card admin2-card-pad mb-4">
                <h5 class="card-title">Demande #<?= htmlspecialchars($demande['id_demande']) ?></h5>
                <p><strong>Ville :</strong> <?= htmlspecialchars($demande['ville'] ?? $demande['id_ville']) ?></p>
                <p><strong>Produit :</strong> <?= htmlspecialchars($demande['produit'] ?? $demande['id_produit']) ?></p>
                <p><strong>Quantité demandée :</strong> <?= htmlspecialchars($demande['quantite_demandee']) ?> <?= htmlspecialchars($demande['unite'] ?? '') ?></p>
                <p><strong>Date de demande :</strong> <?= htmlspecialchars($demande['date_demande']) ?></p>
                <p><strong>Statut :</strong> <?= htmlspecialchars($demande['statut']) ?></p>
        </div>

        <div class="admin2-card mb-4">
            <div class="admin2-card-pad">
                <h5 class="card-title">Dons reçus / distributions déjà effectuées</h5>
                <?php if (empty($distributions)): ?>
                    <div class="text-muted">Aucune distribution enregistrée pour cette demande.</div>
                <?php else: ?>
                    <div class="admin2-table-wrap">
                        <table class="table admin2-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Produit</th>
                                    <th class="text-end">Quantité</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distributions as $dist): ?>
                                    <tr>
                                        <td>
                                            <span class="badge <?= $dist['type'] === 'Achat' ? 'bg-primary' : 'bg-success' ?>">
                                                <?= htmlspecialchars($dist['type']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($dist['produit'] ?? '') ?></td>
                                        <td class="text-end"><strong><?= htmlspecialchars($dist['quantite'] ?? '') ?></strong> <?= htmlspecialchars($dist['unite'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($dist['date_reception'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="admin2-card admin2-card-pad h-100">
                    <h2 class="h5 mb-3">1. Distribution par Stock</h2>
                    <form action="/admin/donner" method="POST">
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Produit</label>
                            <select id="select_produit" name="id_produit" class="form-select">
                                <?php foreach ($produits as $p): ?>
                                    <option value="<?= (int)$p['id_produit'] ?>" <?= ((int)$demande['id_produit'] === (int)$p['id_produit']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nom']) ?> (<?= htmlspecialchars($p['unite']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-secondary mb-0" id="apercu_stock">
                                Stock disponible: <strong id="stock_valeur">-</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantité à distribuer</label>
                            <input id="input_quantite" type="number" step="0.01" class="form-control" name="quantite" required>
                            <div class="form-text" id="texte_validation"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Distribuer du stock</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="admin2-card admin2-card-pad h-100">
                    <h2 class="h5 mb-3">2. Achat par Don Argent</h2>
                    <div class="alert alert-info mb-3">
                        Solde ville: <strong><?= number_format($solde_ville, 2, ',', ' ') ?> Ar</strong>
                    </div>

                    <form action="/admin/acheter" method="POST">
                        <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">

                        <div class="mb-3">
                            <label class="form-label">Produit à acheter</label>
                            <select id="select_produit_achat" name="id_produit" class="form-select">
                                <?php foreach ($categories as $cat): ?>
                                    <optgroup label="<?= htmlspecialchars($cat['nom']) ?>">
                                        <?php 
                                            // Normalement on devrait avoir les produits ici aussi via Repo
                                            // Pour faire simple, on va réutiliser le JS pour filtrer ou charger
                                        ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">L'achat sera débité du solde de la ville.</div>
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-secondary mb-0" id="apercu_prix">
                                Prix unitaire: <strong id="prix_valeur">-</strong> Ar
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantité à acheter</label>
                            <input id="input_quantite_achat" type="number" step="0.01" class="form-control" name="quantite" required>
                            <div class="form-text">Total: <strong id="total_achat">0.00</strong> Ar</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btn_acheter">Acheter & Distribuer</button>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Aucune demande trouvée.</div>
    <?php endif; ?>

        </div>
    </main>

</div>

<script>
    (function() {
        const selectCategorie = document.getElementById('select_categorie');
        const selectProduit = document.getElementById('select_produit');
        const stockValeur = document.getElementById('stock_valeur');
        const inputQuantite = document.getElementById('input_quantite');
        const texteValidation = document.getElementById('texte_validation');
        const apercuStock = document.getElementById('apercu_stock');

        const selectProduitAchat = document.getElementById('select_produit_achat');
        const prixValeur = document.getElementById('prix_valeur');
        const inputQuantiteAchat = document.getElementById('input_quantite_achat');
        const totalAchat = document.getElementById('total_achat');
        const btnAcheter = document.getElementById('btn_acheter');

        let stockActuel = null;
        let mappingProduits = {}; // id -> { prix, unite, nom }
        let soldeVille = <?= (float)$solde_ville ?>;

        async function chargerTousLesProduits() {
            // Pour le bloc achat, on peut vouloir tous les produits
            // ou au moins remplir le select. Ici on va le faire par catégorie pour rester cohérent.
            
            // On va vider et reremplir selectProduitAchat avec tous les produits de toutes les catégories
            selectProduitAchat.innerHTML = '';
            
            const resCat = await fetch('/api/categories');
            const categories = await resCat.json();

            for (const cat of categories) {
                if (cat.nom.toLowerCase() === 'argent') continue; // L'argent est le moyen de paiement, pas le produit

                const group = document.createElement('optgroup');
                group.label = cat.nom;
                
                const resProd = await fetch('/api/produits?categorie=' + cat.id_categorie);
                const produits = await resProd.json();
                
                for (const p of produits) {
                    const opt = document.createElement('option');
                    opt.value = p.id_produit;
                    // Affichage du prix unitaire directement dans le label
                    const prixFmt = Number(p.prix_unitaire).toLocaleString('fr-FR');
                    opt.textContent = `${p.nom} (${prixFmt} Ar / ${p.unite})`;
                    group.appendChild(opt);
                    
                    mappingProduits[p.id_produit] = p;
                }
                selectProduitAchat.appendChild(group);
            }
            
            updatePrixAchat();
        }

        async function chargerProduitsStock() {
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
            validerQuantiteStock();
        }

        function validerQuantiteStock() {
            const q = Number(inputQuantite.value);
            if (stockActuel !== null && q > stockActuel) {
                texteValidation.textContent = 'Quantité trop grande par rapport au stock.';
                apercuStock.classList.add('alert-danger');
                apercuStock.classList.remove('alert-secondary');
            } else {
                texteValidation.textContent = '';
                apercuStock.classList.remove('alert-danger');
                if (inputQuantite.value) apercuStock.classList.add('alert-secondary');
            }
        }

        function updatePrixAchat() {
            const idP = selectProduitAchat.value;
            const p = mappingProduits[idP];
            if (p) {
                prixValeur.textContent = p.prix_unitaire.toLocaleString('fr-FR');
                calculerTotalAchat();
            }
        }

        function calculerTotalAchat() {
            const idP = selectProduitAchat.value;
            const p = mappingProduits[idP];
            const q = Number(inputQuantiteAchat.value);
            if (p) {
                const total = q * p.prix_unitaire;
                totalAchat.textContent = total.toLocaleString('fr-FR', {minimumFractionDigits: 2});
                
                if (total > soldeVille) {
                    totalAchat.parentElement.classList.add('text-danger');
                    btnAcheter.disabled = true;
                } else {
                    totalAchat.parentElement.classList.remove('text-danger');
                    btnAcheter.disabled = false;
                }
            }
        }

        selectCategorie.addEventListener('change', chargerProduitsStock);
        selectProduit.addEventListener('change', chargerStock);
        inputQuantite.addEventListener('input', validerQuantiteStock);

        selectProduitAchat.addEventListener('change', updatePrixAchat);
        inputQuantiteAchat.addEventListener('input', calculerTotalAchat);

        // init
        chargerProduitsStock();
        chargerTousLesProduits();
    })();
</script>
</body>
</html>
