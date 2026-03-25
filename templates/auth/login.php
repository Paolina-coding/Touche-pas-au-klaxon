<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">

    <h2 class="text-center mb-4">Connexion</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-center">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/touche_pas_au_klaxon/public/login" method="POST" class="border p-4 rounded shadow-sm bg-white">

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email"
                   class="form-control"
                   id="email"
                   name="email"
                   required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password"
                   class="form-control"
                   id="password"
                   name="password"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Connexion
        </button>

    </form>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>