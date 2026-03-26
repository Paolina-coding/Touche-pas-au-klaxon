<?php require __DIR__ . '/../../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Créer une nouvelle agence</h1>

    <form action="/touche_pas_au_klaxon/public/admin/agences/create" method="POST" class="card p-4 shadow-sm">

        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input 
                type="text" 
                name="ville" 
                id="ville" 
                class="form-control" 
                required
            >
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-success">
                Créer l'agence
            </button>
        </div>

    </form>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>