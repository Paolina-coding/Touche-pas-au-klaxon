<?php

namespace App\Controllers;

use App\Repositories\AgenceRepository;
use App\Models\Agence;

/**
 * Contrôleur responsable de la gestion des agences.
 * Permet d'afficher la liste des agences, de créer une nouvelle agence, de modifier une agence existante, de supprimer une agence
 */
class AgenceController
{
    /**
     * @var AgenceRepository Repository permettant d'interagir avec la table des agences
     */
    private AgenceRepository $agenceRepo;

    /**
     * Constructeur du contrôleur.
     *
     * @param AgenceRepository $agenceRepo Repository des agences
     */    
    public function __construct(AgenceRepository $agenceRepo)
    {
        $this->agenceRepo = $agenceRepo;
    }

    /**
     * Affiche la liste de toutes les agences.
     *
     * @return void
     */
    public function index(): void
    {
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/admin/agences.php';
    }

    /**
     * Affiche le formulaire de création d'une agence (requête GET).
     *
     * @return void
     */
    public function create(): void
    {
        require __DIR__ . '/../../templates/admin/agences/create.php';
    }

    /**
     * Traite le formulaire de création d'une agence (requête POST).
     *
     * @return void
     */
    public function store(): void
    {
        $ville = trim($_POST['ville'] ?? ''); //récupère la valeur du formulaire et enlève les potentiels espaces

        //si la ville est vide, renvoi du formulaire avec un message d'erreur
        if ($ville === '') {
            $error = "La ville est obligatoire.";
            require __DIR__ . '/../../templates/agence/create.php';
            return;
        }

        //création de l'objet agence
        $agence = new Agence();
        $agence->setVille($ville);

        //appel du repo pour enregistrer dans la base
        $this->agenceRepo->create($agence);

        //redirection une fois l'agence créée
        $_SESSION['flash'] = "L'agence a bien été créée.";
        header('Location: /touche_pas_au_klaxon/public/admin/agences');
        exit;
    }

    /**
     * Affiche le formulaire d'édition d'une agence existante.
     *
     * @param int $id Identifiant de l'agence à modifier
     * @return void
     */
    public function edit(int $id): void
    {
        $agence = $this->agenceRepo->findById($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }

        require __DIR__ . '/../../templates/admin/agences/edit.php';
    }

    /**
     * Traite le formulaire de modification d'une agence.
     *
     * @param int $id Identifiant de l'agence à mettre à jour
     * @return void
     */
    public function update(int $id): void
    {
        $agence = $this->agenceRepo->findById($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }

        $ville = trim($_POST['ville'] ?? '');

        if ($ville === '') {
            $error = "La ville est obligatoire.";
            require __DIR__ . '/../../templates/admin/agences/edit.php';
            return;
        }

        $agence->setVille($ville);
        $this->agenceRepo->update($agence);

        $_SESSION['flash'] = "L'agence a bien été modifiée.";
        header('Location: /touche_pas_au_klaxon/public/admin/agences');
        exit;
    }

    /**
     * Supprime une agence existante.
     *
     * @param int $id Identifiant de l'agence à supprimer
     * @return void
     */
    public function delete(int $id): void
    {   
        $agence = $this->agenceRepo->findById($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }
    
        $this->agenceRepo->delete($id);

        $_SESSION['flash'] = "L'agence a bien été supprimée.";
        header('Location: /touche_pas_au_klaxon/public/admin/agences');
        exit;
    }
}