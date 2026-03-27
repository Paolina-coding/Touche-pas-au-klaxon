Présentation de l'application

Ce projet est une petite application web permettant de gérer des trajets de covoiturage entre employés.  
Elle a été développée en PHP avec une architecture MVC (Modèle – Vue – Contrôleur).

Installation et lancement de l’application

Avant de faire fonctionner l’application, il faut
-	Un éditeur de code, par exemple Visual Studio Code
-	Un serveur local, par exemple WAMP, celui-ci permettra d’avoir PHP (le langage utilisé), Apache (le serveur web) et MySQL (la base de données)
-	Composer, un gestionnaire PHP, celui-ci peut être téléchargé sur le site https://getcomposer.org/download/
Pour installer le projet, il faut
-	Télécharger le projet depuis GitHub (cliquer sur download zip après avoir cliqué sur le bouton <> code) puis extraire le dossier et le placer dans le dossier web du serveur local, pour wamp c’est le dossier www dans le dossier wamp64
-	Ouvrir un terminal dans le dossier du projet (sous window clic droit dans le dossier puis « ouvrir dans le terminal ») et dans ce terminal taper ‘ composer install ‘ ceci installera automatiquement les outils nécessaires
-	Ensuite, dans le dossier du projet, créez un fichier nommé .env et mettez-y 
DB_HOST=localhost
DB_NAME=touche_pas_au_klaxon
DB_USER=root
DB_PASS= (votre mot de passe MySQL)
-	Importer la base de données : ouvrir phpMyAdmin, ouvrir l’interface SQL et coller le contenu du fichier schema_creation.sql
-	Enfin, vous pouvez lancer votre navigateur et aller sur l’adresse http://localhost/touche_pas_au_klaxon/public vous devriez voir la page d’accueil.
