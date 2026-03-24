<?php

namespace App\Repositories;

use App\Models\Agence;
use PDO;

class AgenceRepository
{
    private PDO $db;

    //connexion PDO
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //retourne la liste des agences
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

    //retourne une agence par son id
    public function findById(int $id): ?Agence
    {
        $sql = "SELECT * FROM agence WHERE id_agence = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToAgence($row) : null; //retourne soit l'objet soit null
    }

    //Crée une nouvelle agence
    public function create(Agence $agence): int
    {
        $sql = "
            INSERT INTO agence (ville)
            VALUES (:ville)
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'ville'      => $agence->getVille()
        ]);

        return (int)$this->db->lastInsertId();
    }

    //Met à jour une agence existante
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

    //supprimer une agence existante
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM agence WHERE id_agence = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

// méthode utilitaire

    //transforme les lignes SQL en objet
    private function mapToAgence(array $row): Agence
    {
        $agence = new Agence();

        $agence->setId((int)$row['id_agence']); //PDO renvoie par défaut tout en string
        $agence->setVille($row['ville']);

        return $agence;
    }
}