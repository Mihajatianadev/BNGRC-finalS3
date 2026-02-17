<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Voir tout (détaillé)</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/admin-dashboard.css">
    <link rel="stylesheet" href="/css/login.css">
    <style>
        .cellule-tronquee {
            max-width: 420px;
        }
    </style>
</head>
<body class="admin2-page">

<?php
$chemin_actuel = (string)($_SERVER['REQUEST_URI'] ?? '');
$actif = function ($prefix) use ($chemin_actuel) {
    return strpos($chemin_actuel, $prefix) === 0 ? 'active' : '';
};

$erreur = (string)($_GET['erreur'] ?? '');
$success = (string)($_GET['success'] ?? '');
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
            <div class="admin2-title">Liste détaillée des demandes</div>

            <div class="d-flex align-items-center gap-2" style="flex: 1; justify-content: flex-end;">
                <button type="button" class="btn btn-primary" data-modal-toggle="modal" data-modal-target="#modalDonGlobal">Insérer don global</button>
                <button type="button" class="btn btn-outline-secondary" data-modal-toggle="modal" data-modal-target="#modalBesoin">Ajouter besoin</button>
            </div>

            <div class="admin2-search">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4 1.4-1.4-4.4-4.4A8 8 0 0 0 10 2zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12z"/></svg>
                <input type="text" class="form-control" placeholder="rechercher" aria-label="rechercher">
            </div>
        </div>

        <div class="admin2-content">

            <?php if ($erreur !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>
            <?php if ($success !== ''): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

    <div class="admin2-card admin2-card-pad mb-4">
        <form method="get" class="row g-4 align-items-end">

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

    <div class="admin2-card">
        <div class="admin2-table-wrap">
        <table class="table admin2-table align-middle mb-0">
            <thead>
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
                            <td><span class="admin2-badge"><?= htmlspecialchars($ligne['statut']) ?></span></td>
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
        </div>
    </div>

        </div>
    </main>

</div>

<div class="modal fade" id="modalDonGlobal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 18px;">
      <div class="modal-header">
        <h5 class="modal-title">Insérer don global</h5>
        <button type="button" class="btn-close" data-modal-close aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/admin/don-global" class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Catégorie existante</label>
            <select class="form-select" name="id_categorie" id="dg_id_categorie">
              <option value="">-- Choisir --</option>
            </select>
            <div class="form-text">Ou saisis une nouvelle catégorie.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nouvelle catégorie</label>
            <input class="form-control" name="nouvelle_categorie" placeholder="Ex: Alimentaire">
          </div>

          <div class="col-md-6">
            <label class="form-label">Produit existant</label>
            <select class="form-select" name="id_produit" id="dg_id_produit">
              <option value="">-- Choisir --</option>
            </select>
            <div class="form-text">Le produit existant dépend de la catégorie choisie.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nouveau produit</label>
            <input class="form-control" name="nouveau_produit" placeholder="Ex: Eau potable">
          </div>

          <div class="col-md-6">
            <label class="form-label">Mesure (si nouveau produit)</label>
            <select class="form-select" name="unite_nouveau">
              <option value="">-- Choisir --</option>
              <option value="Kg">Kg</option>
              <option value="L">L</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Quantité</label>
            <input class="form-control" type="number" step="0.01" min="0" name="quantite" required>
          </div>

          <div class="col-12 d-grid">
            <button class="btn btn-primary" type="submit">Valider</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalBesoin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 18px;">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter besoin</h5>
        <button type="button" class="btn-close" data-modal-close aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/admin/besoin" class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Ville</label>
            <select class="form-select" name="id_ville" required>
              <option value="">-- Choisir --</option>
              <?php foreach ($villes as $ville): ?>
                <option value="<?= (int)$ville['id_ville'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Catégorie</label>
            <select class="form-select" name="id_categorie" id="b_id_categorie">
              <option value="">-- Choisir --</option>
            </select>
            <div class="form-text">Ou saisis une nouvelle catégorie.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nouvelle catégorie</label>
            <input class="form-control" name="nouvelle_categorie" placeholder="Ex: Hygiène">
          </div>

          <div class="col-md-6">
            <label class="form-label">Produit (nom)</label>
            <input class="form-control" name="nom_produit" placeholder="Ex: Savon" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Mesure (Kg / L)</label>
            <select class="form-select" name="unite" required>
              <option value="">-- Choisir --</option>
              <option value="Kg">Kg</option>
              <option value="L">L</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Quantité demandée</label>
            <input class="form-control" type="number" step="0.01" min="0" name="quantite" required>
          </div>

          <div class="col-12 d-grid">
            <button class="btn btn-primary" type="submit">Valider</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  function openModal(el){
    if(!el) return;
    el.style.display = 'block';
    el.classList.add('show');
    el.removeAttribute('aria-hidden');
    document.body.classList.add('modal-open');

    if(!document.getElementById('adminModalBackdrop')){
      const bd = document.createElement('div');
      bd.id = 'adminModalBackdrop';
      bd.className = 'modal-backdrop fade show';
      document.body.appendChild(bd);
    }
  }

  function closeModal(el){
    if(!el) return;
    el.classList.remove('show');
    el.setAttribute('aria-hidden', 'true');
    el.style.display = 'none';
    document.body.classList.remove('modal-open');
    const bd = document.getElementById('adminModalBackdrop');
    if(bd) bd.remove();
  }

  document.addEventListener('click', function(e){
    const toggleBtn = e.target && e.target.closest ? e.target.closest('[data-modal-toggle="modal"]') : null;
    if(toggleBtn){
      e.preventDefault();
      const sel = toggleBtn.getAttribute('data-modal-target');
      openModal(document.querySelector(sel));
      return;
    }

    const closeBtn = e.target && e.target.closest ? e.target.closest('[data-modal-close]') : null;
    if(closeBtn){
      e.preventDefault();
      closeModal(closeBtn.closest('.modal'));
      return;
    }

    const modalEl = e.target && e.target.classList && e.target.classList.contains('modal') ? e.target : null;
    if(modalEl && e.target === modalEl){
      closeModal(modalEl);
    }
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
      const open = document.querySelector('.modal.show');
      if(open) closeModal(open);
    }
  });

  async function fetchJson(url){
    const r = await fetch(url, {headers: {'Accept':'application/json'}});
    if(!r.ok) throw new Error('HTTP '+r.status);
    return await r.json();
  }

  async function initCategories(){
    const cats = await fetchJson('/api/categories');
    const selects = [document.getElementById('dg_id_categorie'), document.getElementById('b_id_categorie')].filter(Boolean);
    for(const sel of selects){
      const cur = sel.value;
      sel.innerHTML = '<option value="">-- Choisir --</option>';
      cats.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id_categorie;
        opt.textContent = c.nom;
        sel.appendChild(opt);
      });
      sel.value = cur;
    }
  }

  async function refreshProduitsForDonGlobal(){
    const catSel = document.getElementById('dg_id_categorie');
    const prodSel = document.getElementById('dg_id_produit');
    if(!catSel || !prodSel) return;
    const idCat = parseInt(catSel.value || '0', 10);
    prodSel.innerHTML = '<option value="">-- Choisir --</option>';
    if(!idCat) return;
    const produits = await fetchJson('/api/produits?categorie=' + encodeURIComponent(idCat));
    produits.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.id_produit;
      opt.textContent = p.nom + ' (' + p.unite + ')';
      prodSel.appendChild(opt);
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    initCategories().then(refreshProduitsForDonGlobal).catch(()=>{});
    const catSel = document.getElementById('dg_id_categorie');
    if(catSel){
      catSel.addEventListener('change', function(){
        refreshProduitsForDonGlobal().catch(()=>{});
      });
    }
  });
})();
</script>

</body>
</html>
