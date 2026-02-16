<?php
$chemin_actuel = (string)($_SERVER['REQUEST_URI'] ?? '');
$actif = function ($prefix) use ($chemin_actuel) {
    return strpos($chemin_actuel, $prefix) === 0 ? 'active' : '';
};
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="/admin/voir-tout">Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarAdmin">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= $actif('/admin/voir-tout') ?>" href="/admin/voir-tout">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $actif('/admin/a-propos') ?>" href="/admin/a-propos">A propos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $actif('/admin/dashboard') ?>" href="/admin/dashboard">Distribuer dons</a>
        </li>
      </ul>
      <div class="d-flex">
        <a class="btn btn-outline-light btn-sm" href="/logout">Déconnexion</a>
      </div>
    </div>
  </div>
</nav>
