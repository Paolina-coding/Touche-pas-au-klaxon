<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Repositories\AgenceRepository;
use App\Services\AuthService;
use App\Models\Trajet;
use DateTime;
use Exception;

class TrajetController
{
    private TrajetRepository $trajetRepo;
    private AgenceRepository $agenceRepo;
    private AuthService $authService;

    public function __construct(
        TrajetRepository $trajetRepo,
        AgenceRepository $agenceRepo,
        AuthService $authService)
    {
        $this->trajetRepo = $trajetRepo;
        $this->agenceRepo = $agenceRepo;
        $this->authService = $authService;
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
        $authService = $this->authService;
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/trajets/create.php';
    }

    // traitement du formulaire de création (requête POST)
    public function store(): void
    {
        // Récupération des données du formulaire
        $idVilleDepart = (int)($_POST['agence_depart'] ?? 0);
        $idVilleArrivee = (int)($_POST['agence_arrivee'] ?? 0);
        $dateDepart = trim($_POST['date_depart'] ?? '');
        $dateArrivee = trim($_POST['date_arrivee'] ?? '');
        $placesTotales = (int)($_POST['places_totales'] ?? 0);

        //validations
        $errors = [];

        if ($idVilleDepart === '' || $idVilleArrivee === '') {
            $errors[] = "Les villes de départ et d'arrivée sont obligatoires.";
        }

        if ($idVilleDepart === $idVilleArrivee && $idVilleDepart !== 0) {
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

        $agenceDepart = $this->agenceRepo->findById($idVilleDepart);
        $agenceArrivee = $this->agenceRepo->findById($idVilleArrivee);
        if (!$agenceDepart || !$agenceArrivee) {
            $errors[] = "Une ou plusieurs villes ne correspondent à aucune agence existante.";
        }

        // S'il y a des erreurs on affiche à nouveau le formulaire
        if (!empty($errors)) {
            $authService = $this->authService;
            $agences = $this->agenceRepo->findAll();
            require __DIR__ . '/../../templates/trajets/create.php';
            return;
        }

        // création du trajet
        $trajet = new Trajet();
        $trajet->setIdAgenceDepart($idVilleDepart);
        $trajet->setIdAgenceArrivee($idVilleArrivee);
        $trajet->setDateHeureDepart($dateDepartObj);
        $trajet->setDateHeureArrivee($dateArriveeObj);
        $trajet->setPlacesTotales($placesTotales);
        $trajet->setPlacesDisponibles($placesTotales);
        $trajet->setIdCreateur($this->authService->getUser()->getId());

        $this->trajetRepo->create($trajet);

        // redirection 
        $_SESSION['flash'] = "Le trajet a bien été créé.";
        header('Location: /touche_pas_au_klaxon/public/');
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

        $authService = $this->authService;
        $agences = $this->agenceRepo->findAll();
        
        require __DIR__ . '/../../templates/trajets/edit.php';
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

        // Récupération des données du formulaire
        $idVilleDepart = (int)($_POST['agence_depart'] ?? 0);
        $idVilleArrivee = (int)($_POST['agence_arrivee'] ?? 0);
        $dateDepart = trim($_POST['date_depart'] ?? '');
        $dateArrivee = trim($_POST['date_arrivee'] ?? '');
        $placesTotales = (int)($_POST['places_totales'] ?? 0);
        $placesDisponibles = (int)($_POST['places_disponibles'] ?? 0);

        $errors = [];

        // Validations
        if ($idVilleDepart === 0 || $idVilleArrivee === 0) {
            $errors[] = "Les agences de départ et d'arrivée sont obligatoires.";
        }

        if ($idVilleDepart === $idVilleArrivee) {
            $errors[] = "Les agences de départ et d'arrivée doivent être différentes.";
        }

        if ($dateDepart === '' || $dateArrivee === '') {
            $errors[] = "Les dates de départ et d'arrivée sont obligatoires.";
        }

        try {
            $dateDepartObj = new DateTime($dateDepart);
            $dateArriveeObj = new DateTime($dateArrivee);
        } catch (\Exception $e) {
            $errors[] = "Format de date invalide.";
        }

        if (isset($dateDepartObj, $dateArriveeObj) && $dateDepartObj >= $dateArriveeObj) {
            $errors[] = "La date d'arrivée doit être après la date de départ.";
        }

        if ($placesTotales <= 0) {
            $errors[] = "Le nombre de places doit être supérieur à 0.";
        }

        if ($placesDisponibles < 0) {
            $errors[] = "Les places disponibles ne peuvent pas être négatives.";
        }

        if ($placesDisponibles > $placesTotales) {
            $errors[] = "Les places disponibles ne peuvent pas dépasser les places totales.";
        }

        $agenceDepart = $this->agenceRepo->findById($idVilleDepart);
        $agenceArrivee = $this->agenceRepo->findById($idVilleArrivee);

        if (!$agenceDepart || !$agenceArrivee) {
            $errors[] = "Une ou plusieurs agences n'existent pas.";
        }

        // S'il y a des erreurs on affiche à nouveau le formulaire
        if (!empty($errors)) {
            $authService = $this->authService;
            $agences = $this->agenceRepo->findAll();
            require __DIR__ . '/../../templates/trajets/edit.php';
            return;
        }

        // Mise à jour du trajet
        $trajet->setIdAgenceDepart($idVilleDepart);
        $trajet->setIdAgenceArrivee($idVilleArrivee);
        $trajet->setDateHeureDepart($dateDepartObj);
        $trajet->setDateHeureArrivee($dateArriveeObj);
        $trajet->setPlacesTotales($placesTotales);
        $trajet->setPlacesDisponibles($placesDisponibles);

        $this->trajetRepo->update($trajet);

        $_SESSION['flash'] = "Le trajet a bien été modifié.";

        header('Location: /touche_pas_au_klaxon/public/');
        exit;
    }

    // supprimer un trajet
    public function delete(int $id): void
    {   
        $trajet = $this->trajetRepo->findById($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable";
            return;
        }
    
        $this->trajetRepo->delete($id);

        $_SESSION['flash'] = "Le trajet a bien été supprimé.";

        $user = $this->authService->getUser();
        if ($this->authService->isAdmin()) {
            header('Location: /touche_pas_au_klaxon/public/admin/trajets');
        } else {
            header('Location: /touche_pas_au_klaxon/public/');
        }
        exit;
    }
}