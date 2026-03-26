<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Repositories\AgenceRepository;
use App\Repositories\TrajetRepository;

class AdminController
{
    private UserRepository $userRepo;
    private AgenceRepository $agenceRepo;
    private TrajetRepository $trajetRepo;
    private AuthService $authService;

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

    /** Tableau de bord admin */
    public function dashboard(): void
    {
        $authService = $this->authService;
        require __DIR__ . '/../../templates/admin/dashboard.php';
    }

    /** Liste des utilisateurs */
    public function listUsers(): void
    {
        $authService = $this->authService;
        $users = $this->userRepo->findAll();
        require __DIR__ . '/../../templates/admin/users.php';
    }

    /** Liste des agences */
    public function listAgences(): void
    {
        $authService = $this->authService;
        $agences = $this->agenceRepo->findAll();
        require __DIR__ . '/../../templates/admin/agences.php';
    }

    /** Liste des trajets */
    public function listTrajets(): void
    {
        $authService = $this->authService;
        $trajets = $this->trajetRepo->findAll();
        require __DIR__ . '/../../templates/admin/trajets.php';
    }
}