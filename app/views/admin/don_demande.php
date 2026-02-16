<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la demande & Don</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
    <link rel="stylesheet" href="/css/login.css">
</head>
<body class="admin2-page">

<?php
$chemin_actuel = (string)($_SERVER['REQUEST_URI'] ?? '');
$actif = function ($prefix) use ($chemin_actuel) {
    return strpos($chemin_actuel, $prefix) === 0 ? 'active' : '';
};
?>

<div class="admin2-layout">

    <aside class="admin2-sidebar">
        <div class="admin2-brand" title="Admin">A</div>

        <nav class="admin2-nav">
            <a class="admin2-nav-link <?= $actif('/admin/voir-tout') ?>" href="/admin/voir-tout" title="Accueil">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 3 10v11h7v-7h4v7h7V10L12 3zm7 16h-3v-7H8v7H5v-8l7-5 7 5v8z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/a-propos') ?>" href="/admin/a-propos" title="A propos">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.925 2 3 5.925 3 11s3.925 9 9 9 9-3.925 9-9-3.925-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/dashboard') ?>" href="/admin/dashboard" title="Distribuer dons">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20 7h-2.18A3 3 0 0 0 15 5H9a3 3 0 0 0-2.82 2H4a2 2 0 0 0-2 2v2h2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9h2V9a2 2 0 0 0-2-2zM9 7h6a1 1 0 0 1 1 1v1H8V8a1 1 0 0 1 1-1zm9 14H6v-9h12v9zm2-11H4V9h16v1z"/></svg>
            </a>
        </nav>

        <div class="admin2-sidebar-footer">
            <a class="admin2-logout" href="/logout" title="Déconnexion">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M16 13v-2H7V8l-5 4 5 4v-3h9zm4-10H10a2 2 0 0 0-2 2v4h2V5h10v14H10v-4H8v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/></svg>
            </a>
        </div>
    </aside>

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

        <div class="admin2-card admin2-card-pad">
        <h2 class="h5 mb-3">Faire un don pour cette demande</h2>
        <form action="/admin/donner" method="POST">
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
