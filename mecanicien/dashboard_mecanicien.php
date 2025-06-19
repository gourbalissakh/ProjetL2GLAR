<?php
session_start();
require_once '../Databases/db.php';

// Vérification : Seul un mécanicien connecté peut accéder
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'mecanicien') {
    header("Location: ../Views/connexion.php");
    exit;
}

$nom = $_SESSION['nom'] ?? 'Mécanicien';
$prenom = $_SESSION['prenom'] ?? '';

// Requête : toutes les réparations attribuées à ce mécanicien
$reparations = $pdo->prepare("
  SELECT r.*, v.marque, v.immatriculation, c.nom, c.prenom
  FROM reparation r
  JOIN vehicule v ON r.id_vehicule = v.id
  JOIN utilisateurs c ON v.id_utilisateur = c.id
  WHERE r.id_mecanicien = ?
  ORDER BY r.date_reparation DESC
");
$reparations->execute([$_SESSION['utilisateur_id']]);
$mes_reparations = $reparations->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Mécanicien</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #eef4ff, #dce8f7);
      font-family: 'Segoe UI', sans-serif;
    }
    .hero {
      text-align: center;
      padding: 50px 20px 10px;
    }
    .hero h1 {
      font-size: 2.3rem;
      font-weight: bold;
      color: #0056b3;
    }
    .hero p {
      font-size: 1.1rem;
      color: #555;
    }

    .card-custom {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 20px;
      transition: 0.3s ease;
    }
    .card-custom:hover {
      transform: scale(1.02);
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

<div class="container">
  <div class="hero">
    <img src="../images/logooo.png" alt="Logo" style="max-width: 80px;">
    <h1>Bienvenue, <?= htmlspecialchars($prenom . ' ' . $nom) ?></h1>
    <p>Voici vos réparations en cours ou terminées</p>
  </div>

  <div class="row mt-5">
    <?php if (count($mes_reparations) === 0): ?>
      <div class="col-12">
        <div class="alert alert-info text-center">Aucune réparation ne vous a encore été attribuée.</div>
      </div>
    <?php endif; ?>

    <?php foreach ($mes_reparations as $r): ?>
      <div class="col-md-6">
        <div class="card-custom">
          <h5><i class="bi bi-tools text-primary"></i> Réparation #<?= $r['id'] ?> - <?= htmlspecialchars($r['marque']) ?> (<?= $r['immatriculation'] ?>)</h5>
          <p><strong>Date :</strong> <?= $r['date_reparation'] ?></p>
          <p><strong>Client :</strong> <?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></p>
          <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($r['description'])) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Garage AutoPlus - Tableau de bord Mécanicien
</div>

</body>
</html>