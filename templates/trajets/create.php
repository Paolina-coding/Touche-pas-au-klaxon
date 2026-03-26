<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Créer un nouveau trajet</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-center">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/touche_pas_au_klaxon/public/trajets/create" method="POST" class="card p-4 shadow-sm">

        <!-- Agence de départ -->
        <div class="mb-3">
            <label for="agence_depart" class="form-label">Agence de départ</label>
            <select name="agence_depart" id="agence_depart" class="form-select" required>
                <option value="">Sélectionnez une agence</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= $agence->getId() ?>">
                        <?= htmlspecialchars($agence->getVille()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Agence d'arrivée -->
        <div class="mb-3">
            <label for="agence_arrivee" class="form-label">Agence d'arrivée</label>
            <select name="agence_arrivee" id="agence_arrivee" class="form-select" required>
                <option value="">Sélectionnez une agence</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= $agence->getId() ?>">
                        <?= htmlspecialchars($agence->getVille()) ?> 
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date et heure de départ -->
        <div class="mb-3">
            <label for="date_depart" class="form-label">Date et heure de départ</label>
            <input type="datetime-local" name="date_depart" id="date_depart" class="form-control" required>
        </div>

        <!-- Date et heure d'arrivée -->
        <div class="mb-3">
            <label for="date_arrivee" class="form-label">Date et heure d'arrivée</label>
            <input type="datetime-local" name="date_arrivee" id="date_arrivee" class="form-control" required>
        </div>

        <!-- Places totales -->
        <div class="mb-3">
            <label for="places_totales" class="form-label">Nombre de places totales</label>
            <input type="number" name="places_totales" id="places_totales" class="form-control" min="1" required>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/touche_pas_au_klaxon/public/trajets" class="btn btn-secondary">
                Annuler
            </a>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Créer le trajet
            </button>
        </div>

    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>