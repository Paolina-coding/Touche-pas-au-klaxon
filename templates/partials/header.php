<?php
$isLogged = $authService->isLogged();
$isAdmin  = $authService->isAdmin();
$user = $authService->getUser();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Touche pas au klaxon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="/">
            Touche pas au klaxon
        </a>

        <!-- Bouton burger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto">

                <?php if (!$isLogged): ?>
                    <!-- Visiteur -->
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="/touche_pas_au_klaxon/public/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                        </a>
                    </li>

                <?php elseif ($isAdmin): ?>
                    <!-- Administrateur -->
                    <li class="nav-item"><a class="nav-link" href="/touche_pas_au_klaxon/public/admin/users">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/touche_pas_au_klaxon/public/admin/agences">Agences</a></li>
                    <li class="nav-item"><a class="nav-link" href="/touche_pas_au_klaxon/public/admin/trajets">Trajets</a></li>
                    <li class="nav-item">
                        <a class="nav-link disabled text-white-50">
                            Bonjour <?= htmlspecialchars($user->getPrenom()) ?> <?= htmlspecialchars($user->getNom()) ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-danger" href="/touche_pas_au_klaxon/public/logout">Déconnexion</a>
                    </li>

                <?php else: ?>

                    <!-- Utilisateur connecté -->
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-3" href="/trajets/create">
                            <i class="bi bi-plus-circle me-1"></i> Créer un trajet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled text-white-50">
                            Bonjour <?= htmlspecialchars($user->getPrenom()) ?> <?= htmlspecialchars($user->getNom()) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger ms-3" href="/logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<main class="mt-4">
