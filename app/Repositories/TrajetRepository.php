<?php

namespace App\Repositories;

use App\Models\Trajet;
use PDO;
use DateTime;

class TrajetRepository
{
    private PDO $db;

    //connexion PDO
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //retourne les trajets disponibles (dans le futur et avec assez de places), triés par date croissante
    public function findAllAvailable(): array
    {
        $sql = "
            SELECT *
            FROM trajet
            WHERE places_disponibles > 0
              AND date_heure_depart > NOW()
            ORDER BY date_heure_depart ASC
        ";

        $stmt = $this->db->query($sql); //exécution de la requête SQL dans la BD à laquelle on est connecté
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); //tableau associatif des résultats

        return array_map([$this, 'mapToTrajet'], $rows); //retourne le tableau avec la méthode mapToTrajet appliquée à chaque ligne
    }

    //retourne un trajet par son id
    public function findById(int $id): ?Trajet
    {
        $sql = "SELECT * FROM trajet WHERE id_trajet = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToTrajet($row) : null; //retourne soit l'objet soit null
    }

    //Retourne les trajets créés par un utilisateur.
    public function findByUser(int $userId): array
    {
        $sql = "
            SELECT *
            FROM trajet
            WHERE id_createur = :uid
            ORDER BY date_heure_depart ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapToTrajet'], $rows);
    }

    //Crée un nouveau trajet
    public function create(Trajet $trajet): int
    {
        $sql = "
            INSERT INTO trajet (
                id_createur,
                id_agence_depart,
                id_agence_arrivee,
                date_heure_depart,
                date_heure_arrivee,
                places_totales,
                places_disponibles
            ) VALUES (
                :createur, :depart, :arrivee, :dhd, :dha, :pt, :pd
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'createur' => $trajet->getIdCreateur(),
            'depart' => $trajet->getIdAgenceDepart(),
            'arrivee' => $trajet->getIdAgenceArrivee(),
            'dhd' => $trajet->getDateHeureDepart()->format('Y-m-d H:i:s'),
            'dha' => $trajet->getDateHeureArrivee()->format('Y-m-d H:i:s'),
            'pt' => $trajet->getPlacesTotales(),
            'pd' => $trajet->getPlacesDisponibles(),
        ]);

        return (int)$this->db->lastInsertId();
    }

    //Met à jour un trajet existant
    public function update(Trajet $trajet): bool
    {
        $sql = "
            UPDATE trajet SET
                id_agence_depart = :dep,
                id_agence_arrivee = :arr,
                date_heure_depart = :dhd,
                date_heure_arrivee = :dha,
                places_totales = :pt,
                places_disponibles = :pd
            WHERE id_trajet = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'dep' => $trajet->getIdAgenceDepart(),
            'arr' => $trajet->getIdAgenceArrivee(),
            'dhd' => $trajet->getDateHeureDepart()->format('Y-m-d H:i:s'),
            'dha' => $trajet->getDateHeureArrivee()->format('Y-m-d H:i:s'),
            'pt' => $trajet->getPlacesTotales(),
            'pd' => $trajet->getPlacesDisponibles(),
            'id' => $trajet->getId(),
        ]);
    }

    //supprimer un trajet existant
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM trajet WHERE id_trajet = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

// méthode utilitaire

    //transforme les lignes SQL en objet
    private function mapToTrajet(array $row): Trajet
    {
        $trajet = new Trajet();

        $trajet->setId((int)$row['id_trajet']);
        $trajet->setIdCreateur((int)$row['id_createur']);
        $trajet->setIdAgenceDepart((int)$row['id_agence_depart']);
        $trajet->setIdAgenceArrivee((int)$row['id_agence_arrivee']);
        $trajet->setDateHeureDepart(new DateTime($row['date_heure_depart']));
        $trajet->setDateHeureArrivee(new DateTime($row['date_heure_arrivee']));
        $trajet->setPlacesTotales((int)$row['places_totales']);
        $trajet->setPlacesDisponibles((int)$row['places_disponibles']);

        return $trajet;
    }
}
