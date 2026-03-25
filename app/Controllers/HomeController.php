<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Services\AuthService;

class HomeController
{
    private TrajetRepository $trajetRepo;
    private AuthService $authService;

    public function __construct(TrajetRepository $trajetRepo, AuthService $authService)
    {
        $this->trajetRepo = $trajetRepo;
        $this->authService = $authService;
    }

    public function index(): void
    {
        $trajets = $this->trajetRepo->findAllAvailable();
        require __DIR__ . '/../../templates/home.php';
    }
}