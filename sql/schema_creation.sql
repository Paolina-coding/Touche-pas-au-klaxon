-- création de la base de données
DROP DATABASE IF EXISTS touche_pas_au_klaxon;
CREATE DATABASE touche_pas_au_klaxon;

-- création de l'utilisateur responsable avec tous les droits sur cette base de données (ici le mot de passe a été modifié car le fichier est sur GitHub)
DROP USER IF EXISTS 'responsable'@'localhost';
CREATE USER 'responsable'@'localhost' IDENTIFIED BY '***'; 
GRANT ALL PRIVILEGES ON touche_pas_au_klaxon.* TO 'responsable'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- on se place dans la BDD
USE touche_pas_au_klaxon;

-- création des tables
CREATE TABLE utilisateur (
  id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  telephone VARCHAR(30) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE agence (
  id_agence INT AUTO_INCREMENT PRIMARY KEY,
  ville VARCHAR(255) NOT NULL
);

CREATE TABLE trajet (
  id_trajet INT AUTO_INCREMENT PRIMARY KEY,
  id_createur INT NOT NULL,
  id_agence_depart INT NOT NULL,
  id_agence_arrivee INT NOT NULL,
  date_heure_depart DATETIME NOT NULL,
  date_heure_arrivee DATETIME NOT NULL,
  places_totales INT NOT NULL,
  places_disponibles INT NOT NULL,

  FOREIGN KEY (id_createur) REFERENCES utilisateur(id_utilisateur),
  FOREIGN KEY (id_agence_depart) REFERENCES agence(id_agence),
  FOREIGN KEY (id_agence_arrivee) REFERENCES agence(id_agence)
);