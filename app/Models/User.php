<?php

namespace App\Models;

/**
 * Modèle représentant un utilisateur de l'application.
 *
 * Un utilisateur possède :
 * - un nom et un prénom
 * - un email unique
 * - un numéro de téléphone
 * - un mot de passe (hashé)
 * - un rôle : "admin" ou "user"
 *
 * @property int    $id_utilisateur Identifiant unique de l'utilisateur
 * @property string $nom            Nom de famille
 * @property string $prenom         Prénom
 * @property string $email          Adresse email
 * @property string $telephone      Numéro de téléphone
 * @property string $mot_de_passe   Mot de passe hashé
 * @property string $role           Rôle de l'utilisateur ("admin" ou "user")
 */
class User {
    private int $id_utilisateur;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $mot_de_passe;
    private string $role; // 'admin' ou 'user'

    // GETTERS
    /**
     * Retourne l'identifiant de l'utilisateur.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * Retourne le nom de l'utilisateur.
     *
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * Retourne le prénom de l'utilisateur.
     *
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * Retourne l'adresse email de l'utilisateur.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Retourne le numéro de téléphone.
     *
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * Retourne le mot de passe hashé.
     *
     * @return string
     */
    public function getMotDePasse(): string
    {
        return $this->mot_de_passe;
    }

    /**
     * Retourne le rôle de l'utilisateur.
     *
     * @return string "admin" ou "user"
     */
    public function getRole(): string
    {
        return $this->role;
    }

    // SETTERS
    /**
     * Définit l'identifiant de l'utilisateur.
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id_utilisateur = $id;
    }

    /**
     * Définit le nom de l'utilisateur.
     *
     * @param string $nom
     * @return void
     *
     * @throws \InvalidArgumentException Si le nom dépasse 50 caractères
     */
    public function setNom(string $nom): void
    {
        if (strlen($nom) > 50) {
            throw new \InvalidArgumentException("Le nom ne peut pas dépasser 50 caractères");
        }
        $this->nom = $nom;
    }

    /**
     * Définit le prénom de l'utilisateur.
     *
     * @param string $prenom
     * @return void
     *
     * @throws \InvalidArgumentException Si le prénom dépasse 50 caractères
     */
    public function setPrenom(string $prenom): void
    {
        if (strlen($prenom) > 50) {
            throw new \InvalidArgumentException("Le prénom ne peut pas dépasser 50 caractères");
        }
        $this->prenom = $prenom;
    }

    /**
     * Définit l'adresse email de l'utilisateur.
     *
     * @param string $email
     * @return void
     *
     * @throws \InvalidArgumentException Si l'email est invalide ou trop long
     */
    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email invalide");
        }
        if (strlen($email) > 191) {
            throw new \InvalidArgumentException("L'email ne peut pas dépasser 191 caractères");
        }
        $this->email = $email;
    }

    /**
     * Définit le numéro de téléphone.
     *
     * @param string $telephone
     * @return void
     *
     * @throws \InvalidArgumentException Si le numéro dépasse 20 caractères
     */
    public function setTelephone(string $telephone): void
    {
        if (strlen($telephone) > 20) {
            throw new \InvalidArgumentException("Le numéro de téléphone ne peut pas dépasser 20 caractères");
        }
        $this->telephone = $telephone;
    }

    /**
     * Définit le mot de passe (déjà hashé).
     *
     * @param string $motDePasse
     * @return void
     */
    public function setMotDePasse(string $motDePasse): void
    {
        // Hash à rajouter
        $this->mot_de_passe = $motDePasse;
    }

    /**
     * Définit le rôle de l'utilisateur.
     *
     * @param string $role "admin" ou "user"
     * @return void
     *
     * @throws \InvalidArgumentException Si le rôle est invalide
     */
    public function setRole(string $role): void
    {
        $allowed = ['admin', 'user'];

        if (!in_array($role, $allowed, true)) {
            throw new \InvalidArgumentException("Role invalide, veuillez choisir admin ou user");
        }
        $this->role = $role;
    }
}