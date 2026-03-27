<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\AuthService;

/**
 * Contrôleur responsable de l'authentification des utilisateurs.
 *
 * Gère l'affichage du formulaire de connexion, la validation des identifiants, la connexion de l'utilisateur et la déconnexion
 */
class AuthController
{

    /**
     * @var UserRepository Repository permettant de récupérer les utilisateurs
     */
    private UserRepository $userRepo;

    /**
     * @var AuthService Service d'authentification et gestion des sessions
     */    
    private AuthService $authService;

    /**
     * Constructeur du contrôleur d'authentification.
     *
     * @param UserRepository $userRepo Repository des utilisateurs
     * @param AuthService $authService Service d'authentification
     */
    public function __construct(UserRepository $userRepo, AuthService $authService)
    {
        $this->userRepo = $userRepo;
        $this->authService = $authService;
    }

    /**
     * Affiche le formulaire de connexion.
     *
     * @return void
     */
    public function loginForm(): void
    {
        require __DIR__ . '/../../templates/auth/login.php';
    }

    /**
     * Traite le formulaire de connexion. Vérifie que les champs ne sont pas vides, que l'utilisateur existe et que le mot de passe est correct. 
     * En cas de succès stocke l'ID utilisateur en session et redirige selon le rôle (admin ou utilisateur simple)
     *
     * @return void
     */
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

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /touche_pas_au_klaxon/public/login');
        exit;
    }
}