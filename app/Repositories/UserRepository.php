<?php

namespace App\Repositories;

use App\Models\User;
use PDO;

/**
 * Repository responsable de l'accès aux données des utilisateurs.
 *
 * Fournit des méthodes pour :
 * - récupérer tous les utilisateurs
 * - récupérer un utilisateur par son ID
 * - récupérer un utilisateur par son email
 *
 * Les lignes SQL sont transformées en objets User via mapToUser().
 */
class UserRepository
{
    /**
     * Instance PDO permettant d'interagir avec la base de données.
     *
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructeur du repository.
     *
     * @param PDO $db Connexion PDO active
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Retourne la liste complète des utilisateurs.
     *
     * @return User[] Tableau d'objets User
     */
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

    /**
     * Retourne un utilisateur selon son identifiant.
     *
     * @param int $id Identifiant de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null si inexistant
     */
    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null; //retourne soit l'objet soit null
    }

    /**
     * Retourne un utilisateur selon son email.
     *
     * @param string $email Adresse email de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null si inexistant
     */
    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToUser($row) : null; //retourne soit l'objet soit null
    }

// méthode utilitaire

    /**
     * Transforme une ligne SQL en objet User.
     *
     * @param array $row Ligne SQL sous forme de tableau associatif
     * @return User Objet User correspondant à la ligne
     */
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