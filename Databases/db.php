<?php
// includes/db.php

$host = 'localhost';
$dbname = 'projet_Tutore';
$username = 'root';
$password = '';

try {
    // Connexion à MySQL sans base (pour créer la base si elle n'existe pas)
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS projet_Tutore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Connexion à la base projet_Tutore
    $pdo->exec("USE projet_Tutore");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Création de la table utilisateur
$pdo->exec("CREATE TABLE IF NOT EXISTS utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client', 'mecanicien') NOT NULL
)");

// Création de la table véhicule
$pdo->exec("CREATE TABLE IF NOT EXISTS vehicule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    immatriculation VARCHAR(50) NOT NULL UNIQUE,
    id_utilisateur INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
)");

// Création de la table réparation
$pdo->exec("CREATE TABLE IF NOT EXISTS reparation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vehicule INT NOT NULL,
    date_reparation DATE NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id)
)");

// Création de la table facture
$pdo->exec("CREATE TABLE IF NOT EXISTS facture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reparation INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_facture DATE NOT NULL,
    FOREIGN KEY (id_reparation) REFERENCES reparation(id)
)");

// Création de la table diagnostic
$pdo->exec("CREATE TABLE IF NOT EXISTS diagnostic (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_vehicule INT NOT NULL,
    date_diagnostic DATE NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id)
)");

// Création de la table rendez_vous
$pdo->exec("CREATE TABLE IF NOT EXISTS rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    date_rendez_vous DATE NOT NULL,
    heure_rendez_vous TIME NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
)");

// Insertion des utilisateurs
$utilisateurs = [
    ['issakh', 'gourbal', 'gourbalissakh509@gmail.com', 'admin', 'admin'],
    ['oumar', 'Hamid', 'oumarhamid@gmail.com', 'mecanicien', 'mecanicien'],
    ['mami', 'abdel', 'mamiabdel@gmail.com', 'mecanicien', 'mecanicien'],
    ['okko', 'prinsney', 'okkoprinsney@gmail.com', 'mecanicien', 'mecanicien'],
    ['Alima', 'Sene', 'alimasene@gmail.com', 'client', 'client']
];

foreach ($utilisateurs as $u) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute($u);
}





?>
