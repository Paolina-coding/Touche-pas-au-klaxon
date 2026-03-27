<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

/**
 * Service d'authentification.
 *
 * Ce service permet :
 * - de récupérer l'utilisateur actuellement connecté
 * - de vérifier si un utilisateur est connecté
 * - de vérifier si l'utilisateur connecté est administrateur
 *
 * Il s'appuie sur la session PHP et le UserRepository.
 */
class AuthService
{
    /**
     * Repository permettant de récupérer les utilisateurs.
     *
     * @var UserRepository
     */
    private UserRepository $userRepo;

    /**
     * Constructeur du service d'authentification.
     *
     * @param UserRepository $userRepo Repository des utilisateurs
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Retourne l'utilisateur actuellement connecté.
     *
     * @return User|null L'utilisateur connecté ou null si personne n'est connecté
     */
    public function getUser(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepo->findById($_SESSION['user_id']);
    }

    /**
     * Vérifie si un utilisateur est connecté.
     *
     * @return bool True si un utilisateur est connecté
     */
    public function isLogged(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifie si l'utilisateur connecté est administrateur.
     *
     * @return bool True si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        $user = $this->getUser();
        return $user && $user->getRole() === 'admin';
    }
}