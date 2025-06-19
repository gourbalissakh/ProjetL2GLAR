<?php
// Fonction connectionUtilisateur() permet de connecter un utilisateur(admin, client, mecanicien) à la base de données
function connectionUtilisateur($email, $mot_de_passe) {
    include 'db.php';

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification directe (mot de passe non haché)
        if ($mot_de_passe === $utilisateur['mot_de_passe']) {
            return $utilisateur;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function verifierConnexion($email, $mot_de_passe, $pdo) {
    $query = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        return $user;
    }

    return false;
}

//fonction AjouterClient() permet au admin d'ajouter un client à la base de données
function AjouterClient($nom, $prenom, $email, $mot_de_passe) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Hacher le mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer la requête pour insérer un nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, 'client')");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $hashed_password);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Client ajouté avec succès
    } else {
        return false; // Échec de l'ajout du client
    }
}
// Fonction AjouterMecanicien() permet au admin d'ajouter un mecanicien à la base de données
function AjouterMecanicien($nom, $prenom, $email, $mot_de_passe) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données
    // Hacher le mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    // Préparer la requête pour insérer un nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, 'mecanicien')");
    $stmt->bindParam(':nom', $nom); 
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $hashed_password);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Mécanicien ajouté avec succès
    } else {
        return false; // Échec de l'ajout du mécanicien
    }
}
// Fonction AjouterVehicule() permet au client d'ajouter un véhicule à la base de données
function AjouterVehicule($marque, $immatriculation, $id_utilisateur) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour insérer un nouveau véhicule
    $stmt = $pdo->prepare("INSERT INTO vehicule (marque, immatriculation, id_utilisateur) VALUES (:marque, :immatriculation, :id_utilisateur)");
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':immatriculation', $immatriculation);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Véhicule ajouté avec succès
    } else {
        return false; // Échec de l'ajout du véhicule
    }
}
// Fonction AjouterReparation() permet au mecanicien d'ajouter une réparation à la base de données
function AjouterReparation($id_vehicule, $date_reparation, $description) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour insérer une nouvelle réparation
    $stmt = $pdo->prepare("INSERT INTO reparation (id_vehicule, date_reparation, description) VALUES (:id_vehicule, :date_reparation, :description)");
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->bindParam(':date_reparation', $date_reparation);
    $stmt->bindParam(':description', $description);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Réparation ajoutée avec succès
    } else {
        return false; // Échec de l'ajout de la réparation
    }
}
// Fonction AjouterFacture() permet au mecanicien d'ajouter une facture à la base de données
function AjouterFacture($id_reparation, $montant, $date_facture) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour insérer une nouvelle facture
    $stmt = $pdo->prepare("INSERT INTO facture (id_reparation, montant, date_facture) VALUES (:id_reparation, :montant, :date_facture)");
    $stmt->bindParam(':id_reparation', $id_reparation);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date_facture', $date_facture);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Facture ajoutée avec succès
    } else {
        return false; // Échec de l'ajout de la facture
    }
}
// Fonction AjouterDiagnostic() permet au admin d'ajouter un diagnostic à la base de données
function AjouterDiagnostic($id_reparation, $description) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données
    // Préparer la requête pour insérer un nouveau diagnostic
    $stmt = $pdo->prepare("INSERT INTO diagnostic (id_vehicule, date_diagnostic, description) VALUES (:id_vehicule, :date_diagnostic, :description)");
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->bindParam(':date_diagnostic', $date_diagnostic);
    $stmt->bindParam(':description', $description);
    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Diagnostic ajouté avec succès
    } else {
        return false; // Échec de l'ajout du diagnostic
    }
}
// Fonction pour récupérer tous les utilisateurs de la base de données
function getAllUtilisateurs() {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour récupérer tous les utilisateurs
    $stmt = $pdo->prepare("SELECT * FROM utilisateur");
    $stmt->execute();

    // Retourner les résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Fonction pour récupérer tous les véhicules d'un utilisateur
function getVehiculesByUserId($id_utilisateur) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour récupérer les véhicules d'un utilisateur
    $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->execute();

    // Retourner les résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Fonction pour récupérer toutes les réparations d'un véhicule
function getReparationsByVehiculeId($id_vehicule) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour récupérer les réparations d'un véhicule
    $stmt = $pdo->prepare("SELECT * FROM reparation WHERE id_vehicule = :id_vehicule");
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->execute();

    // Retourner les résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Fonction pour récupérer toutes les factures d'une réparation
function getFacturesByReparationId($id_reparation) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour récupérer les factures d'une réparation
    $stmt = $pdo->prepare("SELECT * FROM facture WHERE id_reparation = :id_reparation");
    $stmt->bindParam(':id_reparation', $id_reparation);
    $stmt->execute();

    // Retourner les résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Fonction pour récupérer tous les diagnostics d'un véhicule
function getDiagnosticsByVehiculeId($id_vehicule) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour récupérer les diagnostics d'un véhicule
    $stmt = $pdo->prepare("SELECT * FROM diagnostic WHERE id_vehicule = :id_vehicule");
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->execute();

    // Retourner les résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Fonction pour supprimer un utilisateur
function supprimerUtilisateur($id_utilisateur) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour supprimer un utilisateur
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = :id_utilisateur");
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Utilisateur supprimé avec succès
    } else {
        return false; // Échec de la suppression de l'utilisateur
    }
}
// Fonction pour supprimer un véhicule
function supprimerVehicule($id_vehicule) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour supprimer un véhicule
    $stmt = $pdo->prepare("DELETE FROM vehicule WHERE id = :id_vehicule");
    $stmt->bindParam(':id_vehicule', $id_vehicule);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Véhicule supprimé avec succès
    } else {
        return false; // Échec de la suppression du véhicule
    }
}
// Fonction pour supprimer une réparation
function supprimerReparation($id_reparation) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour supprimer une réparation
    $stmt = $pdo->prepare("DELETE FROM reparation WHERE id = :id_reparation");
    $stmt->bindParam(':id_reparation', $id_reparation);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Réparation supprimée avec succès
    } else {
        return false; // Échec de la suppression de la réparation
    }
}
// Fonction pour supprimer une facture
function supprimerFacture($id_facture) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour supprimer une facture
    $stmt = $pdo->prepare("DELETE FROM facture WHERE id = :id_facture");
    $stmt->bindParam(':id_facture', $id_facture);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Facture supprimée avec succès
    } else {
        return false; // Échec de la suppression de la facture
    }
}
// Fonction pour supprimer un diagnostic
function supprimerDiagnostic($id_diagnostic) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour supprimer un diagnostic
    $stmt = $pdo->prepare("DELETE FROM diagnostic WHERE id = :id_diagnostic");
    $stmt->bindParam(':id_diagnostic', $id_diagnostic);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Diagnostic supprimé avec succès
    } else {
        return false; // Échec de la suppression du diagnostic
    }
}
// Fonction pour mettre à jour les informations d'un utilisateur
function mettreAJourUtilisateur($id_utilisateur, $nom, $prenom, $email, $mot_de_passe) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Hacher le mot de passe si fourni
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer la requête pour mettre à jour un utilisateur
    $stmt = $pdo->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, mot_de_passe = :mot_de_passe WHERE id = :id_utilisateur");
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $hashed_password);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Utilisateur mis à jour avec succès
    } else {
        return false; // Échec de la mise à jour de l'utilisateur
    }
}
// Fonction pour mettre à jour les informations d'un véhicule
function mettreAJourVehicule($id_vehicule, $marque, $immatriculation) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données
    // Préparer la requête pour mettre à jour un véhicule
    $stmt = $pdo->prepare("UPDATE vehicule SET marque = :marque, immatriculation = :immatriculation WHERE id = :id_vehicule");
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':immatriculation', $immatriculation);
    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Véhicule mis à jour avec succès
    } else {
        return false; // Échec de la mise à jour du véhicule
    }
}
// Fonction pour mettre à jour les informations d'une réparation
function mettreAJourReparation($id_reparation, $id_vehicule, $date_reparation, $description) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour mettre à jour une réparation
    $stmt = $pdo->prepare("UPDATE reparation SET id_vehicule = :id_vehicule, date_reparation = :date_reparation, description = :description WHERE id = :id_reparation");
    $stmt->bindParam(':id_reparation', $id_reparation);
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->bindParam(':date_reparation', $date_reparation);
    $stmt->bindParam(':description', $description);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Réparation mise à jour avec succès
    } else {
        return false; // Échec de la mise à jour de la réparation
    }
}
// Fonction pour mettre à jour les informations d'une facture
function mettreAJourFacture($id_facture, $id_reparation, $montant, $date_facture) { 
    include 'db.php'; // Inclure le fichier de connexion à la base de données

    // Préparer la requête pour mettre à jour une facture
    $stmt = $pdo->prepare("UPDATE facture SET id_reparation = :id_reparation, montant = :montant, date_facture = :date_facture WHERE id = :id_facture");
    $stmt->bindParam(':id_facture', $id_facture);
    $stmt->bindParam(':id_reparation', $id_reparation);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date_facture', $date_facture);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true; // Facture mise à jour avec succès
    } else {
        return false; // Échec de la mise à jour de la facture
    }
}
// Fonction pour mettre à jour les informations d'un diagnostic
function mettreAJourDiagnostic($id_diagnostic, $id_vehicule, $date_diagnostic, $description) {
    include 'db.php'; // Inclure le fichier de connexion à la base de données
    // Préparer la requête pour mettre à jour un diagnostic 
    $stmt = $pdo->prepare("UPDATE diagnostic SET id_vehicule = :id_vehicule, date_diagnostic = :date_diagnostic, description = :description WHERE id = :id_diagnostic");
    $stmt->bindParam(':id_diagnostic', $id_diagnostic);
    $stmt->bindParam(':id_vehicule', $id_vehicule);
    $stmt->bindParam(':date_diagnostic', $date_diagnostic);
    $stmt->bindParam(':description', $description);
    // Exécuter la requête

    if ($stmt->execute()) {
        return true; // Diagnostic mis à jour avec succès
    } else {
        return false; // Échec de la mise à jour du diagnostic
    }
}

function fetchCount($pdo, $query) {
    $stmt = $pdo->query($query);
    $count = $stmt->fetchColumn();
    return $count !== false ? (int)$count : 0;
}

?>