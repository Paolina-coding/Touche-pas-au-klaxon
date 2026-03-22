<?php

namespace App\Models;

class Agence {
    private int $id_agence;
    private string $ville;

    // GETTERS
    public function getId(): int
    {
        return $this->id_agence;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    // SETTERS

    public function setId(int $id): void
    {
        $this->id_agence = $id;
    }

    public function setVille(string $ville): void
    {
        if (strlen($ville) > 255) {
            throw new \InvalidArgumentException("Le nom de la ville ne peut pas dépasser 255 caractères");
        }
        $this->ville = $ville;
    }

}