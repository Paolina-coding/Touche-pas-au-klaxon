<?php

namespace App\Models;

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
    public function getId(): int
    {
        return $this->id_trajet;
    }

    public function getIdCreateur(): int
    {
        return $this->id_createur;
    }

    public function getIdAgenceDepart(): int
    {
        return $this->id_agence_depart;
    }

    public function getIdAgenceArrivee(): int
    {
        return $this->id_agence_arrivee;
    }

    public function getDateHeureDepart(): \DateTime
    {
        return $this->date_heure_depart;
    }

    public function getDateHeureArrivee(): \DateTime
    {
        return $this->date_heure_arrivee;
    }

    public function getPlacesTotales(): int
    {
        return $this->places_totales;
    }

    public function getPlacesDisponibles(): int
    {
        return $this->places_disponibles;
    }

    // SETTERS

    public function setId(int $id): void
    {
        $this->id_trajet = $id;
    }

    public function setIdCreateur(int $id_createur): void
    {
        if ($id_createur <= 0) {
            throw new \InvalidArgumentException("ID créateur invalide");
        }
        $this->id_createur = $id_createur;
    }

    public function setIdAgenceDepart(int $id_agence_depart): void
    {
        if ($id_agence_depart <= 0) {
            throw new \InvalidArgumentException("ID agence invalide");
        }
        $this->id_agence_depart = $id_agence_depart;
    }

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
    
    public function setDateHeureDepart(\DateTime $date_heure_depart): void
    {
        $this->date_heure_depart = $date_heure_depart;
    }

    public function setDateHeureArrivee(\DateTime $date_heure_arrivee): void
    {
        if (isset($this->date_heure_depart) && $date_heure_arrivee <= $this->date_heure_depart) { // vérification que date_heure_depart existe avant d'essayer de comparer
            throw new \InvalidArgumentException("La date d'arrivée doit être après la date de départ");
        }
        $this->date_heure_arrivee = $date_heure_arrivee;
    }

    public function setPlacesTotales(int $places_totales): void
    {
        if ($places_totales <= 0) {
            throw new \InvalidArgumentException("Le nombre de places totales doit être positif");
        }
        $this->places_totales = $places_totales;
    }

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