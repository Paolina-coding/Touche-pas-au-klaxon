<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Repositories\AgenceRepository;
use App\Repositories\TrajetRepository;

/**
 * Contrôleur dédié aux fonctionnalités d'administration.
 * Permet à un administrateur de consulter le tableau de bord, la liste des utilisateurs, la liste des agences, la liste des trajets
 */
class AdminController
{
    /**
     * @var UserRepository Repository pour la gestion des utilisateurs
     */
    private UserRepository $userRepo;

    /**
     * @var AgenceRepository Repository pour la gestion des agences
     */
    private AgenceRepository $agenceRepo;

    /**
     * @var TrajetRepository Repository pour la gestion des trajets
     */
    private TrajetRepository $trajetRepo;

    /**
     * @var AuthService Service d'authentification et de gestion des sessions
     */
    private AuthService $authService;

    /**
     * Constructeur du contrôleur admin.
     *
     * @param UserRepository $userRepo Repository des utilisateurs
     * @param AgenceRepository $agenceRepo Repository des agences
     * @param TrajetRepository $trajetRepo Repository des trajets
     * @param AuthService $authService Service d'authentification
     */    
    public function __construct(
        UserRepository $userRepo,
        AgenceRepository $agenceRepo,
        TrajetRepository $trajetRepo,
        AuthService $authService
    ) {
        $this->userRepo = $userRepo;
        $this->agenceRepo = $agenceRepo;
        $this->trajetRepo = $trajetRepo;
        $this->authService = $authService;
    }

    /**
     * Affiche le tableau de bord administrateur.
     *
     * @return void
     */
    public function dashboard(): void
    {
        $authService = $this->authService;
        require __DIR__ . '/../../templates/admin/dashboard.php';
    }

    /**
     * Affiche la liste de tous les utilisateurs.
     *
     * @return void
     */
    public function listUsers(): void
    {
        $authService = $this->authService;
        $users = $this->userRepo->findAll();
        require __DIR__ . '/../../templates/admin/users.php';
    }

    /**
     * Affiche la liste de toutes les agences.
     *
     * @return void
     */
    public function listAgences(): void
    {
        $authService = $this->authService;
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/admin/agences.php';
    }

    /**
     * Affiche la liste de tous les trajets.
     *
     * @return void
     */
    public function listTrajets(): void
    {
        $authService = $this->authService;
        $trajets = $this->trajetRepo->findAll();
        require __DIR__ . '/../../templates/admin/trajets.php';
    }
}