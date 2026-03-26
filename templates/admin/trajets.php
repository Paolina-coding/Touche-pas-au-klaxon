<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gestion des trajets</h1>
    
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['flash'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if (empty($trajets)): ?>
        <div class="alert alert-info">Aucun trajet trouvé.</div>
    <?php else: ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Arrivée</th>
                    <th>Date</th>
                    <th>Créateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= $trajet['id_trajet'] ?></td>
                        <td><?= htmlspecialchars($trajet['depart_nom']) ?></td>
                        <td><?= htmlspecialchars($trajet['date_heure_depart']) ?></td>
                        <td><?= htmlspecialchars($trajet['arrivee_nom']) ?></td>
                        <td><?= htmlspecialchars($trajet['date_heure_arrivee']) ?></td>
                        <td><?= htmlspecialchars($trajet['createur_prenom'] . ' ' . $trajet['createur_nom']) ?></td>
                        <td>
                            <a href="/touche_pas_au_klaxon/public/admin/trajets/delete/<?= $trajet['id_trajet'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer ce trajet ?');">
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