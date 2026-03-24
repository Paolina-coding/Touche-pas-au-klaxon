<?php

namespace App\Repositories;

use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    //connexion PDO
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //retourne la liste des users
    public function findAll(): array
    {
        $sql = "
            SELECT *
            FROM utilisateur
        ";

        $stmt = $this->db->query($sql); //exécution de la requête SQL dans la BD à laquelle on est connecté
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); //tableau associatif des résultats

        return array_map([$this, 'mapToUser'], $rows); //retourne le tableau avec la méthode mapToUser appliquée à chaque ligne
    }

    //retourne un user par son id
    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null; //retourne soit l'objet soit null
    }

    //retourne un user par son email
    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null; //retourne soit l'objet soit null
    }

// méthode utilitaire

    //transforme les lignes SQL en objet
    private function mapToUser(array $row): User
    {
        $user  = new User();

        $user->setId((int)$row['id_utilisateur']);
        $user->setNom($row['nom']);
        $user->setPrenom($row['prenom']);
        $user->setEmail($row['email']);
        $user->setTelephone($row['telephone']);
        $user->setMotDePasse($row['mot_de_passe']);
        $user->setRole($row['role']);

        return $user;
    }
}