<?php

namespace App\Controllers;

use App\Repositories\TrajetRepository;
use App\Models\Trajet;

class TrajetController
{
    public function index(): void
        {
            $trajets = $this->trajetRepo->findAllAvailable();
            require __DIR__ . '/../templates/home.php';
        }
}