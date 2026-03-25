<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Models\Trajet;

class TrajetController
{
    private TrajetRepository $trajetRepo;

    public function __construct(TrajetRepository $trajetRepo)
    {
        $this->trajetRepo = $trajetRepo;
    }

    // afficher la liste des trajets
    public function index(): void
    {
        $trajets = $this->trajetRepo->findAll();
        require __DIR__ . '/../../templates/admin/trajets.php';
    }

    //afficher les détails d'un trajet
    public function show($id): void
    {
        $trajet = $this->trajetRepo->findById($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable";
            return;
        }

        require __DIR__ . '/../../templates/trajet/infos.php';
    }

    // afficher le formulaire de création (requête GET)
    public function create(): void
    {
        require __DIR__ . '/../../templates/trajet/create.php';
    }

    // traitement du formulaire de création (requête POST)
    public function store(): void
    {
        // Récupération des données du formulaire
        $villeDepart = trim($_POST['agence_depart'] ?? '');
        $villeArrivee = trim($_POST['agence_arrivee'] ?? '');
        $dateDepart = trim($_POST['date_depart'] ?? '');
        $dateArrivee = trim($_POST['date_arrivee'] ?? '');
        $placesTotales = (int)($_POST['places_totales'] ?? 0);

        $agenceDepart = $this->agenceRepo->findByVille($villeDepart);
        $agenceArrivee = $this->agenceRepo->findByVille($villeArrivee);

        //validations
        $errors = [];

        if ($villeDepart === '' || $villeArrivee === '') {
            $errors[] = "Les villes de départ et d'arrivée sont obligatoires.";
        }

        if ($villeDepart !== '' && $villeArrivee !== '' && strtolower($villeDepart) === strtolower($villeArrivee)) {
            $errors[] = "Les villes de départ et d'arrivée doivent être différentes.";
        }

        if ($dateDepart === '' || $dateArrivee === '') {
            $errors[] = "Les dates de départ et d'arrivée sont obligatoires.";
        }

        try {
            $dateDepartObj = new DateTime($dateDepart);
            $dateArriveeObj = new DateTime($dateArrivee);
        } catch (Exception $e) {
            $errors[] = "Format de date invalide.";
        }

        if (isset($dateDepartObj, $dateArriveeObj) && $dateDepartObj >= $dateArriveeObj) {
            $errors[] = "La date d'arrivée doit être après la date de départ.";
        }

        if ($placesTotales <= 0) {
            $errors[] = "Le nombre de places doit être supérieur à 0.";
        }

        if (!$agenceDepart || !$agenceArrivee) {
            $errors[] = "Une ou plusieurs villes ne correspondent à aucune agence existante.";
        }

        // S'il y a des erreurs on affiche à nouveau le formulaire
        if (!empty($errors)) {
            require __DIR__ . '/../../templates/trajet/create.php';
            return;
        }

        // création du trajet
        $trajet = new Trajet();
        $trajet->setIdAgenceDepart($agenceDepart->getId());
        $trajet->setIdAgenceArrivee($agenceArrivee->getId());
        $trajet->setDateHeureDepart($dateDepartObj);
        $trajet->setDateHeureArrivee($dateArriveeObj);
        $trajet->setPlacesTotales($placesTotales);
        $trajet->setPlacesDisponibles($placesTotales);
        $trajet->setIdCreateur($this->authService->getUser()->getId());

        $this->trajetRepo->create($trajet);

        // redirection 
        header('Location: /admin/trajets');
        exit;
    }
    

    // Afficher le formulaire pour modifier une trajet
    public function edit(int $id): void
    {
        $trajet = $this->trajetRepo->findById($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable";
            return;
        }

        require __DIR__ . '/../../templates/trajet/edit.php';
    }

    // traitement du formulaire pour modifier une trajet
    public function update(int $id): void
    {
        $trajet = $this->trajetRepo->findById($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable";
            return;
        }

        $ville = trim($_POST['ville'] ?? '');

        if ($ville === '') {
            $error = "La ville est obligatoire.";
            require __DIR__ . '/../../templates/trajet/edit.php';
            return;
        }

        $trajet->setVille($ville);
        $this->trajetRepo->update($trajet);

        header('Location: /admin/trajets');
        exit;
    }

    // supprimer une trajet
    public function delete(int $id): void
    {   
        $trajet = $this->trajetRepo->findById($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable";
            return;
        }
    
        $this->trajetRepo->delete($id);

        header('Location: /admin/trajets');
        exit;
    }
}