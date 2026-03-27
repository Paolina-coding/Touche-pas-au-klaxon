<?php

namespace App\Models;

/**
 * Modèle représentant une agence.
 *
 * Une agence correspond à une ville dans laquelle un trajet peut
 * commencer ou se terminer.
 *
 * @property int    $id_agence  Identifiant unique de l'agence
 * @property string $ville      Nom de la ville associée à l'agence
 */

class Agence 
{
    /**
     * Identifiant unique de l'agence.
     *
     * @var int
     */
    private int $id_agence;

    /**
     * Nom de la ville associée à l'agence.
     *
     * @var string
     */
    private string $ville;

    // GETTERS
    /**
     * Retourne l'identifiant de l'agence.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id_agence;
    }

    /**
     * Retourne le nom de la ville.
     *
     * @return string
     */
    public function getVille(): string
    {
        return $this->ville;
    }

    // SETTERS

    /**
     * Définit l'identifiant de l'agence.
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id_agence = $id;
    }

    /**
     * Définit le nom de la ville.
     *
     * @param string $ville
     * @return void
     *
     * @throws \InvalidArgumentException Si la ville dépasse 255 caractères
     */
    public function setVille(string $ville): void
    {
        if (strlen($ville) > 255) {
            throw new \InvalidArgumentException("Le nom de la ville ne peut pas dépasser 255 caractères");
        }
        $this->ville = $ville;
    }

}