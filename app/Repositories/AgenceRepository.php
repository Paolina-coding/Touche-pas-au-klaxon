<?php

namespace App\Repositories;

use App\Models\Agence;
use PDO;

/**
 * Repository responsable de l'accès aux données des agences.
 *
 * Fournit des méthodes pour :
 * - récupérer toutes les agences
 * - récupérer une agence par son ID
 * - récupérer une agence par son nom de ville
 * - créer une agence
 * - mettre à jour une agence
 * - supprimer une agence
 *
 * Ce repository transforme les lignes SQL en objets Agence via mapToAgence().
 */
class AgenceRepository
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
     * Retourne la liste complète des agences.
     *
     * @return Agence[] Tableau d'objets Agence
     */
    public function findAll(): array
    {
        $sql = "
            SELECT *
            FROM agence
        ";

        $stmt = $this->db->query($sql); //exécution de la requête SQL dans la BD à laquelle on est connecté
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); //tableau associatif des résultats

        return array_map([$this, 'mapToAgence'], $rows); //retourne le tableau avec la méthode mapToAgence appliquée à chaque ligne
    }

    /**
     * Retourne une agence selon son identifiant.
     *
     * @param int $id Identifiant de l'agence
     * @return Agence|null L'agence trouvée ou null si inexistante
     */
    public function findById(int $id): ?Agence
    {
        $sql = "SELECT * FROM agence WHERE id_agence = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToAgence($row) : null; //retourne soit l'objet soit null
    }

    /**
     * Retourne une agence selon son nom de ville.
     *
     * @param string $ville Nom de la ville
     * @return Agence|null L'agence trouvée ou null si inexistante
     */
    public function findByVille(string $ville): ?Agence
    {
        $sql = "SELECT * FROM agence WHERE ville = :ville";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ville' => $ville]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToAgence($row) : null; //retourne soit l'objet soit null
    }

    /**
     * Crée une nouvelle agence dans la base de données.
     *
     * @param Agence $agence Objet Agence à insérer
     * @return int ID de l'agence nouvellement créée
     */
    public function create(Agence $agence): int
    {
        $sql = "
            INSERT INTO agence (ville)
            VALUES (:ville)
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'ville' => $agence->getVille()
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Met à jour une agence existante.
     *
     * @param Agence $agence Objet Agence contenant les nouvelles valeurs
     * @return bool True si la mise à jour a réussi
     */
    public function update(Agence $agence): bool
    {
        $sql = "
            UPDATE agence SET
                ville = :ville
            WHERE id_agence = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $agence->getId(),
            'ville' => $agence->getVille()
        ]);
    }

    /**
     * Supprime une agence selon son identifiant.
     *
     * @param int $id Identifiant de l'agence
     * @return bool True si la suppression a réussi
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM agence WHERE id_agence = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

// méthode utilitaire

    /**
     * Transforme une ligne SQL en objet Agence.
     *
     * @param array $row Ligne SQL sous forme de tableau associatif
     * @return Agence Objet Agence correspondant à la ligne
     */
    private function mapToAgence(array $row): Agence
    {
        $agence = new Agence();

        $agence->setId((int)$row['id_agence']); //PDO renvoie par défaut tout en string
        $agence->setVille($row['ville']);

        return $agence;
    }
}