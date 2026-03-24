<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class AuthService
{
    //on veut pouvoir récupérer l'utilisateur
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getUser(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepo->findById($_SESSION['user_id']);
    }

    //vérification si quelqu'un est connecté
    public function isLogged(): bool
    {
        return isset($_SESSION['user_id']);
    }

    //vérification si l'utilisateur connecté est un admin
    public function isAdmin(): bool
    {
        $user = $this->getUser();
        return $user && $user->getRole() === 'admin';
    }
}