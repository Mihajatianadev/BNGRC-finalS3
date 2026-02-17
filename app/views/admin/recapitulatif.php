<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif - BNGRC</title>
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

    <?php require __DIR__ . '/_navbar.php'; ?>

    <main class="admin2-main">
        <div class="admin2-topbar">
            <div class="admin2-title">Tableau Récapitulatif (Montants en Ar)</div>
            <button id="btn_refresh" class="btn btn-success">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="me-2" style="margin-right: 8px;"><path d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                Actualiser 
            </button>
        </div>

        <div class="admin2-content">
            <div class="admin2-card admin2-card-pad mb-4">
                <form id="recap_filter" class="row g-4 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label">Ville</label>
                        <select id="filtre_ville" class="form-select">
                            <option value="">Toutes les villes</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Date début</label>
                        <input id="filtre_date_debut" type="date" class="form-control">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Date fin</label>
                        <input id="filtre_date_fin" type="date" class="form-control">
                    </div>
                    <div class="col-lg-2 d-grid">
                        <button type="submit" class="btn btn-primary">Appliquer</button>
                    </div>
                </form>
                <div class="form-text">Le filtre date s'applique aux distributions et achats.</div>
            </div>

            <section class="mb-5">
                <h3 class="h5 mb-3">Par Ville</h3>
                <div class="admin2-card">
                    <div class="admin2-table-wrap">
                        <table class="table admin2-table mb-0">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Région</th>
                                    <th class="text-end">Besoins Totaux</th>
                                    <th class="text-end">Besoins Satisfaits</th>
                                    <th class="text-end">Dons Reçus (Arg)</th>
                                    <th class="text-end">Dons Distr. (Ach)</th>
                                </tr>
                            </thead>
                            <tbody id="body_villes">
                                <tr><td colspan="6" class="text-center">Cliquer sur "Actualiser" pour charger les données.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <h3 class="h5 mb-3">Par Région</h3>
                <div class="admin2-card">
                    <div class="admin2-table-wrap">
                        <table class="table admin2-table mb-0">
                            <thead>
                                <tr>
                                    <th>Région</th>
                                    <th class="text-end">Besoins Totaux</th>
                                    <th class="text-end">Besoins Satisfaits</th>
                                    <th class="text-end">Dons Reçus (Arg)</th>
                                    <th class="text-end">Dons Distr. (Ach)</th>
                                </tr>
                            </thead>
                            <tbody id="body_regions">
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="h5 mb-3">Global</h3>
                <div class="admin2-card admin2-card-pad bg-light">
                    <div class="row text-center" id="total_recap">
                        <div class="col-md-3">
                            <div class="small fw-bold text-muted">Besoins Totaux</div>
                            <div class="h4 mb-0" id="total_besoins">0</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small fw-bold text-muted">Besoins Satisfaits</div>
                            <div class="h4 mb-0" id="total_satisfaits">0</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small fw-bold text-muted">Dons Reçus (Argent)</div>
                            <div class="h4 mb-0" id="total_recus">0</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small fw-bold text-muted">Dons Distribués</div>
                            <div class="h4 mb-0" id="total_distribues">0</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    <?php require __DIR__ . '/footer.php'; ?>
    </main>
</div>

<script>
    window.__VILLES = <?= json_encode($villes ?? [], JSON_UNESCAPED_UNICODE) ?>;
    (function() {
        const btnRefresh = document.getElementById('btn_refresh');
        const bodyVilles = document.getElementById('body_villes');
        const bodyRegions = document.getElementById('body_regions');
        
        const totalBesoins = document.getElementById('total_besoins');
        const totalSatisfaits = document.getElementById('total_satisfaits');
        const totalRecus = document.getElementById('total_recus');
        const totalDistribues = document.getElementById('total_distribues');

        async function fetchRecap() {
            btnRefresh.disabled = true;
            const originalHtml = btnRefresh.innerHTML;
            btnRefresh.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Actualisation...';
            
            try {
                const ville = (document.getElementById('filtre_ville')?.value || '').trim();
                const dateDebut = (document.getElementById('filtre_date_debut')?.value || '').trim();
                const dateFin = (document.getElementById('filtre_date_fin')?.value || '').trim();

                const url = new URL('/api/recap', window.location.origin);
                if (ville !== '') url.searchParams.set('ville', ville);
                if (dateDebut !== '') url.searchParams.set('date_debut', dateDebut);
                if (dateFin !== '') url.searchParams.set('date_fin', dateFin);

                const res = await fetch(url.toString());
                const data = await res.json();
                
                renderVilles(data.villes);
                renderRegions(data.regions);
                renderTotal(data.total);
            } catch (err) {
                console.error(err);
                alert('Erreur lors du chargement des données.');
            } finally {
                btnRefresh.disabled = false;
                btnRefresh.innerHTML = originalHtml;
            }
        }

        function renderVilles(villes) {
            bodyVilles.innerHTML = '';
            villes.forEach(v => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${v.ville}</td>
                    <td>${v.region}</td>
                    <td class="text-end">${formatAr(v.besoins_totaux_montant)}</td>
                    <td class="text-end">${formatAr(v.besoins_satisfaits_montant)}</td>
                    <td class="text-end">${formatAr(v.dons_recus_montant)}</td>
                    <td class="text-end">${formatAr(v.dons_distribues_montant)}</td>
                `;
                bodyVilles.appendChild(tr);
            });
        }

        function renderRegions(regions) {
            bodyRegions.innerHTML = '';
            regions.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${r.region}</td>
                    <td class="text-end">${formatAr(r.besoins_totaux_montant)}</td>
                    <td class="text-end">${formatAr(r.besoins_satisfaits_montant)}</td>
                    <td class="text-end">${formatAr(r.dons_recus_montant)}</td>
                    <td class="text-end">${formatAr(r.dons_distribues_montant)}</td>
                `;
                bodyRegions.appendChild(tr);
            });
        }

        function renderTotal(t) {
            totalBesoins.textContent = formatAr(t.besoins_totaux_montant);
            totalSatisfaits.textContent = formatAr(t.besoins_satisfaits_montant);
            totalRecus.textContent = formatAr(t.dons_recus_montant);
            totalDistribues.textContent = formatAr(t.dons_distribues_montant);
        }

        function formatAr(val) {
            return Number(val).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }) + ' Ar';
        }

        function chargerVilles() {
            const sel = document.getElementById('filtre_ville');
            if (!sel) return;
            const villes = (window.__VILLES && Array.isArray(window.__VILLES)) ? window.__VILLES : [];
            villes.forEach(v => {
                const opt = document.createElement('option');
                opt.value = String(v.id_ville);
                opt.textContent = v.nom;
                sel.appendChild(opt);
            });
        }

        btnRefresh.addEventListener('click', fetchRecap);
        const filterForm = document.getElementById('recap_filter');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e){
                e.preventDefault();
                fetchRecap();
            });
        }

        chargerVilles();
    })();
</script>
</body>
</html>
