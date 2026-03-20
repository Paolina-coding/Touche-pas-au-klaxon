-- on se place dans la BDD
USE touche_pas_au_klaxon;

-- comme le fichier est disponible sur github les mots de passe sont changés
INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role) VALUES
    ('Martin', 'Alexandre', 'alexandre.martin@email.fr', '0612345678', '$2y$10$hash', 'user'),
    ('Dubois', 'Sophie', 'sophie.dubois@email.fr', '0698765432', '$2y$10$hash', 'user'),
    ('Bernard', 'Julien', 'julien.bernard@email.fr', '0622446688', '$2y$10$hash', 'user'),
    ('Moreau', 'Camille', 'camille.moreau@email.fr', '0611223344', '$2y$10$hash', 'user'),
    ('Lefèvre', 'Lucie', 'lucie.lefevre@email.fr', '0777889900', '$2y$10$hash', 'user'),
    ('Leroy', 'Thomas', 'thomas.leroy@email.fr', '0655443322', '$2y$10$hash', 'user'),
    ('Roux', 'Chloé', 'chloe.roux@email.fr', '0633221199', '$2y$10$hash', 'user'),
    ('Petit', 'Maxime', 'maxime.petit@email.fr', '0766778899', '$2y$10$hash', 'user'),
    ('Garnier', 'Laura', 'laura.garnier@email.fr', '0688776655', '$2y$10$hash', 'user'),
    ('Dupuis', 'Antoine', 'antoine.dupuis@email.fr', '0744556677', '$2y$10$hash', 'user'),
    ('Lefebvre', 'Emma', 'emma.lefebvre@email.fr', '0699887766', '$2y$10$hash', 'user'),
    ('Fontaine', 'Louis', 'louis.fontaine@email.fr', '0655667788', '$2y$10$hash', 'user'),
    ('Chevalier', 'Clara', 'clara.chevalier@email.fr', '0788990011', '$2y$10$hash', 'user'),
    ('Robin', 'Nicolas', 'nicolas.robin@email.fr', '0644332211', '$2y$10$hash', 'user'),
    ('Gauthier', 'Marine', 'marine.gauthier@email.fr', '0677889922', '$2y$10$hash', 'user'),
    ('Fournier', 'Pierre', 'pierre.fournier@email.fr', '0722334455', '$2y$10$hash', 'user'),
    ('Girard', 'Sarah', 'sarah.girard@email.fr', '0688665544', '$2y$10$hash', 'user'),
    ('Lambert', 'Hugo', 'hugo.lambert@email.fr', '0611223366', '$2y$10$hash', 'user'),
    ('Masson', 'Julie', 'julie.masson@email.fr', '0733445566', '$2y$10$hash', 'user'),
    ('Henry', 'Arthur', 'arthur.henry@email.fr', '0666554433', '$2y$10$hash', 'user');

INSERT INTO agence (ville) VALUES
    ('Paris'),
    ('Lyon'),
    ('Marseille'),
    ('Toulouse'),
    ('Nice'),
    ('Nantes'),
    ('Strasbourg'),
    ('Montpellier'),
    ('Bordeaux'),
    ('Lille'),
    ('Rennes'),
    ('Reims');