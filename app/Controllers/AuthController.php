<?php

namespace App\Controllers;


class AuthController
{
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

        if (!$user || !password_verify($password, $user->getPassword())) {
            $errors[] = "Identifiants incorrects.";
            require __DIR__ . '/../../templates/auth/login.php';
            return;
        }

        // Connexion
        $_SESSION['user_id'] = $user->getId();

        // Redirection selon le rôle
        if ($user->getRole() === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /');
        }

        exit;
    }

    //déconnexion
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}