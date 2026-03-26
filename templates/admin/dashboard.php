<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Tableau de bord administrateur</h1>

    <div class="row g-4">

        <!-- Utilisateurs -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 text-primary"></i>
                    <h4 class="mt-3">Utilisateurs</h4>
                    <p class="text-muted">Gérer les utilisateurs: voir la liste des utilisateurs.</p>
                    <a href="/touche_pas_au_klaxon/public/admin/users" class="btn btn-primary w-100">
                        Accéder à la gestion des utilisateurs
                    </a>
                </div>
            </div>
        </div>

        <!-- Agences -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-building display-4 text-success"></i>
                    <h4 class="mt-3">Agences</h4>
                    <p class="text-muted">Gérer les agences: voir la liste, créer, modifier ou supprimer une agence</p>
                    <a href="/touche_pas_au_klaxon/public/admin/agences" class="btn btn-success w-100 mb-2">
                        Accéder à la gestion des agences
                    </a>
                </div>
            </div>
        </div>

        <!-- Trajets -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-car-front-fill display-4 text-warning"></i>
                    <h4 class="mt-3">Trajets</h4>
                    <p class="text-muted">Gérer les trajets: voir la liste et supprimer les trajets.</p>
                    <a href="/touche_pas_au_klaxon/public/admin/trajets" class="btn btn-warning w-100 mb-2">
                        Accéder à la gestion des trajets
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>