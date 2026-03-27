<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Repositories\AgenceRepository;
use App\Services\AuthService;
use App\Models\Trajet;
use DateTime;
use Exception;

/**
 * Contrôleur responsable de la gestion des trajets.
 *
 * Permet d'afficher la liste des trajets, de consulter les détails d'un trajet, de créer un trajet, de modifier un trajet et de supprimer un trajet
 */
class TrajetController
{
    /**
     * @var TrajetRepository Repository permettant d'interagir avec les trajets
     */
    private TrajetRepository $trajetRepo;

    /**
     * @var AgenceRepository Repository permettant de récupérer les agences
     */
    private AgenceRepository $agenceRepo;

    /**
     * @var AuthService Service d'authentification et gestion des sessions
     */
    private AuthService $authService;

    /**
     * Constructeur du contrôleur des trajets.
     *
     * @param TrajetRepository  $trajetRepo   Repository des trajets
     * @param AgenceRepository  $agenceRepo   Repository des agences
     * @param AuthService       $authService  Service d'authentification
     */
    public function __construct(
        TrajetRepository $trajetRepo,
        AgenceRepository $agenceRepo,
        AuthService $authService)
    {
        $this->trajetRepo = $trajetRepo;
        $this->agenceRepo = $agenceRepo;
        $this->authService = $authService;
    }


    /**
     * Affiche la liste de tous les trajets (vue admin).
     *
     * @return void
     */
    public function index(): void
    {
        $trajets = $this->trajetRepo->findAll();
        require __DIR__ . '/../../templates/admin/trajets.php';
    }

    /**
     * Affiche les détails d'un trajet.
     *
     * @param int $id Identifiant du trajet
     * @return void
     */
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

    /**
     * Affiche le formulaire de création d'un trajet.
     *
     * @return void
     */
    public function create(): void
    {
        $authService = $this->authService;
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/trajets/create.php';
    }


    /**
     * Traite le formulaire de création d'un trajet.
     * Valide les agences de départ et d'arrivée, les dates et le nombre de places
     * Crée ensuite un objet Trajet et l'enregistre via le repository.
     *
     * @return void
     */
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
    


    /**
     * Affiche le formulaire de modification d'un trajet.
     *
     * @param int $id Identifiant du trajet
     * @return void
     */
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

    /**
     * Traite le formulaire de modification d'un trajet.
     * Valide les agences de départ et d'arrivée, les dates et le nombre de places
     * 
     * @param int $id Identifiant du trajet à modifier
     * @return void
     */
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

    /**
     * Supprime un trajet existant.
     *
     * @param int $id Identifiant du trajet à supprimer
     * @return void
     */
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