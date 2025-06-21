<?php
session_start();
require_once '../Databases/db.php';

// Sécurité : Vérifie que c’est bien un client
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../Views/connexion.php");
    exit;
}

$nom = $_SESSION['nom'] ?? 'Client';
$prenom = $_SESSION['prenom'] ?? '';

// Requête pour récupérer les véhicules du client
$vehicules = $pdo->prepare("SELECT * FROM vehicule WHERE id_utilisateur = ?");
$vehicules->execute([$_SESSION['utilisateur_id']]);
$mes_vehicules = $vehicules->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les réparations en cours
$reparations = $pdo->prepare("
  SELECT r.*, v.marque, v.immatriculation 
  FROM reparation r 
  JOIN vehicule v ON r.id_vehicule = v.id 
  WHERE v.id_utilisateur = ?
  ORDER BY r.date_reparation DESC
");
$reparations->execute([$_SESSION['utilisateur_id']]);
$mes_reparations = $reparations->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord - Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f5f9ff, #e2ecf5);
      font-family: 'Segoe UI', sans-serif;
    }
    .hero {
      text-align: center;
      padding: 40px 20px 10px;
    }
    .hero h1 {
      font-size: 2.3rem;
      font-weight: bold;
      color: #0056b3;
    }
    .hero p {
      font-size: 1.1rem;
      color: #666;
    }

    .card-box {
      border: none;
      border-radius: 15px;
      padding: 30px 20px;
      text-align: center;
      transition: 0.3s ease;
      background: #fff;
      box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .card-box:hover {
      transform: scale(1.03);
    }
    .bg-blue {
      background: linear-gradient(135deg, #007bff, #00c6ff);
      color: white;
    }
    .bg-green {
      background: linear-gradient(135deg, #28a745, #a2f4b8);
      color: white;
    }
    .footer {
      text-align: center;
      padding: 20px;
      background: #7397bb;
      color: white;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <!-- appeler le header client -->
   <?php include 'header_client.php'; ?>


<div class="container my-4">
  <div class="hero">
    <img src="../images/logooo.png" alt="Logo Garage AutoPlus" style="max-width: 80px;">
    <h1>Bienvenue, <?= htmlspecialchars($prenom . ' ' . $nom) ?></h1>
    <p>Voici un aperçu de vos véhicules et réparations en cours.</p>
  </div>

  <div class="row my-5">
    <div class="col-md-6 mb-4">
      <div class="card-box bg-blue">
        <i class="bi bi-car-front-fill display-5"></i>
        <h4 class="mt-3"><?= count($mes_vehicules) ?> Véhicule(s)</h4>
        <p>Enregistrés sous votre nom</p>
        <a href="mes_vehicules.php" class="btn btn-light btn-sm mt-2">Voir mes véhicules</a>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card-box bg-green">
        <i class="bi bi-tools display-5"></i>
        <h4 class="mt-3"><?= count($mes_reparations) ?> Réparation(s)</h4>
        <p>En cours ou terminées</p>
        <a href="mes_reparations.php" class="btn btn-light btn-sm mt-2">Voir les réparations</a>
      </div>
    </div>
  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Garage AutoPlus - Tableau de bord client
</div>

</body>
</html>
