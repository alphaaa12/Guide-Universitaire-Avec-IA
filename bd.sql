CREATE DATABASE gestion_utilisateurs;

USE gestion_utilisateurs;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    faculte VARCHAR(100),
    num_inscription VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('etudiant', 'enseignant', 'personne', 'admin') NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Ajouter un compte admin
INSERT INTO utilisateurs (nom, prenom, faculte, num_inscription, email, role, mot_de_passe)
VALUES ('Admin', 'Principal', 'Informatique', '0000', 'admin@gmail.com', 'admin', SHA2('admin1234', 256));

CREATE TABLE espaces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO espaces (nom) VALUES ('faculte'), ('resto'), ('foyer'), ('car');

admins
id
nom
email
mot_de_passe
role
 




 /*
CREATE TABLE admin_espaces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    espace_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (espace_id) REFERENCES espaces(id)
);*/