<?php

namespace App\Models;

class User {
    private int $id_utilisateur;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private string $mot_de_passe;
    private string $role; // 'admin' ou 'user'

    // GETTERS
    public function getId(): int
    {
        return $this->id_utilisateur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getMotDePasse(): string
    {
        return $this->mot_de_passe;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // SETTERS

    public function setId(int $id): void
    {
        $this->id_utilisateur = $id;
    }

    public function setNom(string $nom): void
    {
        if (strlen($nom) > 50) {
            throw new \InvalidArgumentException("Le nom ne peut pas dépasser 50 caractères");
        }
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        if (strlen($prenom) > 50) {
            throw new \InvalidArgumentException("Le prénom ne peut pas dépasser 50 caractères");
        }
        $this->prenom = $prenom;
    }

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

    public function setTelephone(string $telephone): void
    {
        if (strlen($telephone) > 20) {
            throw new \InvalidArgumentException("Le numéro de téléphone ne peut pas dépasser 20 caractères");
        }
        $this->telephone = $telephone;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        // Hash à rajouter
        $this->mot_de_passe = $motDePasse;
    }

    public function setRole(string $role): void
    {
        $allowed = ['admin', 'user'];

        if (!in_array($role, $allowed, true)) {
            throw new \InvalidArgumentException("Role invalide, veuillez choisir admin ou user");
        }
        $this->role = $role;
    }
}