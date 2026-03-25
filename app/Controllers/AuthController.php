<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\AuthService;

class AuthController
{
    private UserRepository $userRepo;
    private AuthService $authService;

    public function __construct(UserRepository $userRepo, AuthService $authService)
    {
        $this->userRepo = $userRepo;
        $this->authService = $authService;
    }

    //formulaire de connexion
    public function loginForm(): void
    {
        require __DIR__ . '/../../templates/auth/login.php';
    }

    // traitement du formulaire de connexion
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $errors = [];

        if ($email === '' || $password === '') {
            $errors[] = "Email et mot de passe obligatoires.";
            require __DIR__ . '/../../templates/auth/login.php';
            return;
        }

        // Vérification utilisateur
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user->getMotDePasse())) {
            $errors[] = "Identifiants incorrects.";
            require __DIR__ . '/../../templates/auth/login.php';
            return;
        }

        // Connexion
        $_SESSION['user_id'] = $user->getId();

        // Redirection selon le rôle
        if ($user->getRole() === 'admin') {
            header('Location: /touche_pas_au_klaxon/public/admin');
        } else {
            header('Location: /touche_pas_au_klaxon/public/');
        }

        exit;
    }

    //déconnexion
    public function logout(): void
    {
        session_destroy();
        header('Location: /touche_pas_au_klaxon/public/login');
        exit;
    }
}