<?php

namespace App\Controllers;

use App\Repositories\AgenceRepository;
use App\Models\Agence;

class AgenceController
{
    private AgenceRepository $agenceRepo;

    public function __construct(AgenceRepository $agenceRepo)
    {
        $this->agenceRepo = $agenceRepo;
    }

    // afficher la liste des agences
    public function index(): void
    {
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/admin/agences.php';
    }

    // afficher le formulaire de création (requête GET)
    public function create(): void
    {
        require __DIR__ . '/../../templates/agence/create.php';
    }

    // traitement du formulaire de création (requête POST)
    public function store(): void
    {
        $ville = trim($_POST['ville'] ?? ''); //récupère la valeur du formulaire et enlève les potentiels espaces

        //si la ville est vide, renvoi du formulaire avec un message d'erreur
        if ($ville === '') {
            $error = "La ville est obligatoire.";
            require __DIR__ . '/../../templates/agence/create.php';
            return;
        }

        //création de l'objet ville
        $agence = new Agence();
        $agence->setVille($ville);

        //appel du repo pour enregistrer dans la base
        $this->agenceRepo->create($agence);

        //redirection une fois l'agence créée
        header('Location: /admin/agences');
        exit;
    }

    // Afficher le formulaire pour modifier une agence
    public function edit(int $id): void
    {
        $agence = $this->agenceRepo->findById($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }

        require __DIR__ . '/../../templates/agence/edit.php';
    }

    // traitement du formulaire pour modifier une agence
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
            require __DIR__ . '/../../templates/agence/edit.php';
            return;
        }

        $agence->setVille($ville);
        $this->agenceRepo->update($agence);

        header('Location: /admin/agences');
        exit;
    }

    // supprimer une agence
    public function delete(int $id): void
    {   
        $agence = $this->agenceRepo->findById($id);

        if (!$agence) {
            http_response_code(404);
            echo "Agence introuvable";
            return;
        }
    
        $this->agenceRepo->delete($id);

        header('Location: /admin/agences');
        exit;
    }
}