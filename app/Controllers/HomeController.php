<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Services\AuthService;

/**
 * Contrôleur de la page d'accueil.
 * Affiche la liste des trajets disponibles pour les utilisateurs connectés.
 */
class HomeController
{
    /**
     * @var TrajetRepository Repository permettant de récupérer les trajets disponibles
     */
    private TrajetRepository $trajetRepo;

    /**
     * @var AuthService Service d'authentification et gestion des sessions
     */
    private AuthService $authService;

    /**
     * Constructeur du contrôleur d'accueil.
     *
     * @param TrajetRepository $trajetRepo Repository des trajets
     * @param AuthService $authService Service d'authentification
     */
    public function __construct(TrajetRepository $trajetRepo, AuthService $authService)
    {
        $this->trajetRepo = $trajetRepo;
        $this->authService = $authService;
    }

    /**
     * Affiche la page d'accueil avec la liste des trajets disponibles.
     *
     * @return void
     */
    public function index(): void
    {
        $authService = $this->authService;
        $trajets = $this->trajetRepo->findAllAvailable();
        require __DIR__ . '/../../templates/home.php';
    }
}