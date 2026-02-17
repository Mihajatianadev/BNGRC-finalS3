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
    <aside class="admin2-sidebar">
        <div class="admin2-brand" title="Admin">A</div>
        <nav class="admin2-nav">
            <a class="admin2-nav-link <?= $actif('/admin/voir-tout') ?>" href="/admin/voir-tout" title="Accueil">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3 3 10v11h7v-7h4v7h7V10L12 3zm7 16h-3v-7H8v7H5v-8l7-5 7 5v8z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/a-propos') ?>" href="/admin/a-propos" title="A propos">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M11 17h2v-6h-2v6zm0-8h2V7h-2v2zm1-7C6.925 2 3 5.925 3 11s3.925 9 9 9 9-3.925 9-9-3.925-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/dashboard') ?>" title="Demandes en attente" href="/admin/dashboard">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20 7h-2.18A3 3 0 0 0 15 5H9a3 3 0 0 0-2.82 2H4a2 2 0 0 0-2 2v2h2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9h2V9a2 2 0 0 0-2-2zM9 7h6a1 1 0 0 1 1 1v1H8V8a1 1 0 0 1 1-1zm9 14H6v-9h12v9zm2-11H4V9h16v1z"/></svg>
            </a>
            <a class="admin2-nav-link <?= $actif('/admin/recapitulatif') ?>" title="Récapitulatif" href="/admin/recapitulatif">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
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
            <div class="admin2-title">Tableau Récapitulatif (Montants en Ar)</div>
            <button id="btn_refresh" class="btn btn-success">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="me-2" style="margin-right: 8px;"><path d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                Actualiser (Ajax)
            </button>
        </div>

        <div class="admin2-content">
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
    </main>
</div>

<script>
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
                const res = await fetch('/api/recap');
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

        btnRefresh.addEventListener('click', fetchRecap);
    })();
</script>
</body>
</html>
