<?php require __DIR__ . '/partials/header.php'; ?>

<div class="container mt-4">

    <?php if (!$authService->isLogged()): ?>
        <div class="alert alert-info text-center">
            Pour obtenir plus d'informations sur un trajet, veuillez vous connecter.
        </div>
    <?php endif; ?>

    <h2 class="mb-4">Trajets disponibles</h2>

    <?php if (empty($trajets)): ?>
        <div class="alert alert-warning text-center">
            Aucun trajet disponible pour le moment.
        </div>
    <?php else: ?>

        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Heure</th>

                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>

                    <th>Places</th>

                    <?php if ($authService->isLogged()): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <!-- Départ -->
                        <td><?= htmlspecialchars($trajet['depart_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($trajet['date_heure_depart'])) ?></td>
                        <td><?= date('H:i', strtotime($trajet['date_heure_depart'])) ?></td>

                        <!-- Arrivée -->
                        <td><?= htmlspecialchars($trajet['arrivee_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($trajet['date_heure_arrivee'])) ?></td>
                        <td><?= date('H:i', strtotime($trajet['date_heure_arrivee'])) ?></td>

                        <!-- Places -->
                        <td><?= (int)$trajet['places_disponibles'] ?></td>

                        <?php if ($authService->isLogged()): ?>
                            <td>

                                <!-- Infos -->
                                <i class="bi bi-eye-fill text-info fs-5 me-2"
                                    role="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalInfos<?= $trajet['id_trajet'] ?>">
                                </i>

                                <!-- Si l'utilisateur est l'auteur -->
                                <?php if ($trajet['id_createur'] == $authService->getUser()->getId()): ?>

                                    <!-- Modifier -->
                                    <a href="/touche_pas_au_klaxon/public/trajets/edit?id=<?= $trajet['id_trajet'] ?>"
                                    class="text-warning me-2">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <a href="/touche_pas_au_klaxon/public/trajets/delete?id=<?= $trajet['id_trajet'] ?>"
                                    class="text-danger"
                                    onclick="return confirm('Supprimer ce trajet ?');">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </a>

                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- MODALE INFOS -->
                    <div class="modal fade" id="modalInfos<?= $trajet['id_trajet'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Informations complémentaires</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <p><strong>Auteur :</strong> <?= htmlspecialchars($trajet['createur_prenom'] . ' ' . $trajet['createur_nom']) ?></p>
                                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($trajet['createur_tel']) ?></p>
                                    <p><strong>Email :</strong> <?= htmlspecialchars($trajet['createur_email']) ?></p>
                                    <p><strong>Nombre total de places :</strong> <?= (int)$trajet['places_totales'] ?></p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>