<?php

namespace App\Models;

/**
 * Modèle représentant un trajet de covoiturage.
 *
 * Un trajet contient :
 * - une agence de départ
 * - une agence d'arrivée
 * - une date/heure de départ
 * - une date/heure d'arrivée
 * - un nombre total de places
 * - un nombre de places disponibles
 * - un créateur (utilisateur)
 *
 * @property int $id_trajet Identifiant unique du trajet
 * @property int $id_createur Identifiant de l'utilisateur créateur
 * @property int $id_agence_depart Identifiant de l'agence de départ
 * @property int $id_agence_arrivee Identifiant de l'agence d'arrivée
 * @property DateTime $date_heure_depart Date et heure de départ
 * @property DateTime $date_heure_arrivee Date et heure d'arrivée
 * @property int $places_totales Nombre total de places
 * @property int $places_disponibles Nombre de places encore disponibles
 */
class Trajet {
    private int $id_trajet;
    private int $id_createur;
    private int $id_agence_depart;
    private int $id_agence_arrivee;
    private \DateTime $date_heure_depart;
    private \DateTime $date_heure_arrivee;
    private int $places_totales;
    private int $places_disponibles;

    // GETTERS
    /**
     * Retourne l'identifiant du trajet.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id_trajet;
    }

    /**
     * Retourne l'identifiant du créateur du trajet.
     *
     * @return int
     */
    public function getIdCreateur(): int
    {
        return $this->id_createur;
    }

    /**
     * Retourne l'identifiant de l'agence de départ.
     *
     * @return int
     */
    public function getIdAgenceDepart(): int
    {
        return $this->id_agence_depart;
    }

    /**
     * Retourne l'identifiant de l'agence d'arrivée.
     *
     * @return int
     */
    public function getIdAgenceArrivee(): int
    {
        return $this->id_agence_arrivee;
    }

    /**
     * Retourne la date et l'heure de départ.
     *
     * @return DateTime
     */
    public function getDateHeureDepart(): \DateTime
    {
        return $this->date_heure_depart;
    }

    /**
     * Retourne la date et l'heure d'arrivée.
     *
     * @return DateTime
     */
    public function getDateHeureArrivee(): \DateTime
    {
        return $this->date_heure_arrivee;
    }

    /**
     * Retourne le nombre total de places.
     *
     * @return int
     */
    public function getPlacesTotales(): int
    {
        return $this->places_totales;
    }

    /**
     * Retourne le nombre de places disponibles.
     *
     * @return int
     */
    public function getPlacesDisponibles(): int
    {
        return $this->places_disponibles;
    }

    // SETTERS
    /**
     * Définit l'identifiant du trajet.
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id_trajet = $id;
    }

    /**
     * Définit l'identifiant du créateur du trajet.
     *
     * @param int $id_createur
     * @return void
     *
     * @throws \InvalidArgumentException Si l'ID est invalide
     */
    public function setIdCreateur(int $id_createur): void
    {
        if ($id_createur <= 0) {
            throw new \InvalidArgumentException("ID créateur invalide");
        }
        $this->id_createur = $id_createur;
    }

    /**
     * Définit l'agence de départ.
     *
     * @param int $id_agence_depart
     * @return void
     *
     * @throws \InvalidArgumentException Si l'ID est invalide
     */
    public function setIdAgenceDepart(int $id_agence_depart): void
    {
        if ($id_agence_depart <= 0) {
            throw new \InvalidArgumentException("ID agence invalide");
        }
        $this->id_agence_depart = $id_agence_depart;
    }

    /**
     * Définit l'agence d'arrivée.
     *
     * @param int $id_agence_arrivee
     * @return void
     *
     * @throws \InvalidArgumentException Si l'ID est invalide ou identique à l'agence de départ
     */
    public function setIdAgenceArrivee(int $id_agence_arrivee): void
    {
        if ($id_agence_arrivee <= 0) {
            throw new \InvalidArgumentException("ID agence invalide");
        }
        if (isset($this->id_agence_depart) && $id_agence_arrivee === $this->id_agence_depart) {
            throw new \InvalidArgumentException("L'agence de départ et d'arrivée doivent être différentes");
        }
        $this->id_agence_arrivee = $id_agence_arrivee;
    }
    
    /**
     * Définit la date et l'heure de départ.
     *
     * @param DateTime $date_heure_depart
     * @return void
     */
    public function setDateHeureDepart(\DateTime $date_heure_depart): void
    {
        $this->date_heure_depart = $date_heure_depart;
    }

    /**
     * Définit la date et l'heure d'arrivée.
     *
     * @param DateTime $date_heure_arrivee
     * @return void
     *
     * @throws \InvalidArgumentException Si la date d'arrivée est avant la date de départ
     */
    public function setDateHeureArrivee(\DateTime $date_heure_arrivee): void
    {
        if (isset($this->date_heure_depart) && $date_heure_arrivee <= $this->date_heure_depart) { // vérification que date_heure_depart existe avant d'essayer de comparer
            throw new \InvalidArgumentException("La date d'arrivée doit être après la date de départ");
        }
        $this->date_heure_arrivee = $date_heure_arrivee;
    }

    /**
     * Définit le nombre total de places.
     *
     * @param int $places_totales
     * @return void
     *
     * @throws \InvalidArgumentException Si le nombre est invalide
     */
    public function setPlacesTotales(int $places_totales): void
    {
        if ($places_totales <= 0) {
            throw new \InvalidArgumentException("Le nombre de places totales doit être positif");
        }
        $this->places_totales = $places_totales;
    }

    /**
     * Définit le nombre de places disponibles.
     *
     * @param int $places_disponibles
     * @return void
     *
     * @throws \InvalidArgumentException Si le nombre est invalide
     */
    public function setPlacesDisponibles(int $places_disponibles): void
    {
        if ($places_disponibles < 0) {
            throw new \InvalidArgumentException("Les places disponibles ne peuvent pas être négatives");
        }
        if (isset($this->places_totales) && $places_disponibles > $this->places_totales) {
            throw new \InvalidArgumentException("Les places disponibles ne peuvent pas dépasser les places totales");
        }
        $this->places_disponibles = $places_disponibles;
    } 
}