<?php

namespace App\Repositories;

use App\Models\Trajet;
use PDO;
use DateTime;

/**
 * Repository responsable de l'accès aux données des trajets.
 *
 * Fournit des méthodes pour :
 * - récupérer tous les trajets (avec infos agences + créateur)
 * - récupérer les trajets disponibles (futurs + places restantes)
 * - récupérer un trajet par son ID
 * - récupérer les trajets créés par un utilisateur
 * - créer, mettre à jour et supprimer un trajet
 *
 * Les méthodes mapToTrajet() transforment les lignes SQL en objets Trajet.
 */
class TrajetRepository
{
    /**
     * Connexion PDO active.
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
     * Retourne la liste complète des trajets, avec :
     * - agences de départ et d'arrivée
     * - informations du créateur
     *
     *
     * @return array Liste des trajets sous forme de tableau associatif
     */
    public function findAll(): array
    {
        $sql = "
            SELECT 
                t.*,

                a1.ville AS depart_nom,
                a2.ville AS arrivee_nom,

                u.prenom AS createur_prenom,
                u.nom AS createur_nom

            FROM trajet t
            JOIN agence a1 ON t.id_agence_depart = a1.id_agence
            JOIN agence a2 ON t.id_agence_arrivee = a2.id_agence
            JOIN utilisateur u ON t.id_createur = u.id_utilisateur

            ORDER BY t.date_heure_depart ASC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne les trajets disponibles (date future, places disponibles > 0, avec infos agences et créateur)
     *
     * @return array Liste des trajets disponibles
     */
    public function findAllAvailable(): array
    {
        $sql = "
            SELECT 
                t.*,

                a1.ville AS depart_nom,
                a2.ville AS arrivee_nom,

                u.prenom AS createur_prenom,
                u.nom AS createur_nom,
                u.email AS createur_email,
                u.telephone AS createur_tel

            FROM trajet t
            JOIN agence a1 ON t.id_agence_depart = a1.id_agence
            JOIN agence a2 ON t.id_agence_arrivee = a2.id_agence
            JOIN utilisateur u ON t.id_createur = u.id_utilisateur

            WHERE t.places_disponibles > 0
            AND t.date_heure_depart > NOW()

            ORDER BY t.date_heure_depart ASC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne un trajet par son identifiant.
     *
     * @param int $id Identifiant du trajet
     * @return Trajet|null Objet Trajet ou null si non trouvé
     */
    public function findById(int $id): ?Trajet
    {
        $sql = "SELECT * FROM trajet WHERE id_trajet = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]); //requête avec paramètre dynamique donc protection, pas de query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToTrajet($row) : null; //retourne soit l'objet soit null
    }

    /**
     * Retourne les trajets créés par un utilisateur donné.
     *
     * @param int $userId Identifiant du créateur
     * @return Trajet[] Liste d'objets Trajet
     */
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

    /**
     * Crée un nouveau trajet dans la base de données.
     *
     * @param Trajet $trajet Objet Trajet à insérer
     * @return int ID du trajet nouvellement créé
     */
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

    /**
     * Met à jour un trajet existant.
     *
     * @param Trajet $trajet Objet Trajet contenant les nouvelles valeurs
     * @return bool True si la mise à jour a réussi
     */
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

    /**
     * Supprime un trajet selon son identifiant.
     *
     * @param int $id Identifiant du trajet
     * @return bool True si la suppression a réussi
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM trajet WHERE id_trajet = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

// méthode utilitaire

    /**
     * Transforme une ligne SQL en objet Trajet.
     *
     * @param array $row Ligne SQL sous forme de tableau associatif
     * @return Trajet Objet Trajet correspondant à la ligne
     */
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
