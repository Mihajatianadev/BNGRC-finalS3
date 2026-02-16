<?php
// Simple registration page used by AuthController
// Expects `$values`, `$errors`, and `$success` provided by controller
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="stylesheet" href="/assets/main-BQhM7myw.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">LOGIN</h1>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">Inscription réussie.</div>
            <?php endif; ?>

            <?php if (!empty($errors['_global'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errors['_global']); ?></div>
            <?php endif; ?>

            <form method="post" action="/register" novalidate>
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input name="nom" class="form-control" value="<?php echo htmlspecialchars($values['nom'] ?? ''); ?>">
                    <div class="text-danger small"><?php echo htmlspecialchars($errors['nom'] ?? ''); ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input name="prenom" class="form-control" value="<?php echo htmlspecialchars($values['prenom'] ?? ''); ?>">
                    <div class="text-danger small"><?php echo htmlspecialchars($errors['prenom'] ?? ''); ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="<?php echo htmlspecialchars($values['email'] ?? ''); ?>">
                    <div class="text-danger small"><?php echo htmlspecialchars($errors['email'] ?? ''); ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rôle</label>
                    <input name="email" type="text" class="form-control" value="<?php echo htmlspecialchars($values['role'] ?? ''); ?>">
                    <div class="text-danger small"><?php echo htmlspecialchars($errors['role'] ?? ''); ?></div>
                </div>

                <button class="btn btn-primary">Se connecter </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
