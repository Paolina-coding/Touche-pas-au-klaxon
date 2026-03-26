<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gestion des agences</h1>

    <!-- Message flash -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['flash'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Bouton créer -->
    <div class="mb-3">
        <a href="/touche_pas_au_klaxon/public/admin/agences/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une agence
        </a>
    </div>

    <?php if (empty($agences)): ?>
        <div class="alert alert-info">Aucune agence trouvée.</div>
    <?php else: ?>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agences as $agence): ?>
                    <tr>
                        <td><?= $agence->getId() ?></td>
                        <td><?= htmlspecialchars($agence->getVille()) ?></td>

                        <td>
                            <!-- Modifier -->
                            <a href="/touche_pas_au_klaxon/public/admin/agences/edit/<?= $agence->getId() ?>"
                               class="btn btn-warning btn-sm">
                                Modifier
                            </a>

                            <!-- Supprimer -->
                            <a href="/touche_pas_au_klaxon/public/admin/agences/delete/<?= $agence->getId() ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer cette agence ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>