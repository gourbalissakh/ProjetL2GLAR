<?php
session_start();
require_once '../Databases/db.php';

// Vérifie si client connecté
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Views/connexion.php');
    exit;
}

$clientId = $_SESSION['utilisateur_id'];
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';

// Récupérer les véhicules du client
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_utilisateur = ? ORDER BY marque, immatriculation");
$stmt->execute([$clientId]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Véhicules - Garage AutoPlus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap + Icônes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
   
<?php include 'header_client.php'; ?>

  <style>
    :root {
      --gmi-color: rgb(115, 151, 187);
      --gmi-color-dark: rgb(88, 119, 149);
      --gmi-bg-light: #f1f7fb;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--gmi-bg-light);
      padding: 20px;
    }

   

    
    h1 {
      color: var(--gmi-color-dark);
      font-weight: 700;
    }

    .card-vehicule {
      background: white;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      padding: 20px;
    }

    .card-vehicule:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .card-title {
      font-weight: bold;
      color: var(--gmi-color-dark);
    }

    .btn-secondary {
      border-radius: 30px;
    }
  </style>
  
</head>
<body>




<div class="container">
  <h1 class="mb-4"><i class="bi bi-car-front-fill me-2"></i>Mes Véhicules</h1>

  <?php if (count($vehicules) === 0): ?>
    <div class="alert alert-info shadow-sm">Vous n'avez aucun véhicule enregistré.</div>
  <?php else: ?>
    <div class="row gy-4">
      <?php foreach ($vehicules as $v): ?>
        <div class="col-md-4 animate__animated animate__fadeInUp">
          <div class="card-vehicule">
            <h5 class="card-title"><i class="bi bi-truck-front-fill me-2"></i><?= htmlspecialchars($v['marque']) ?></h5>
            <p><strong>Immatriculation :</strong> <?= htmlspecialchars($v['immatriculation']) ?></p>
            <?php if (!empty($v['model'])): ?>
              <p><strong>Modèle :</strong> <?= htmlspecialchars($v['model']) ?></p>
            <?php endif; ?>
            <?php if (!empty($v['année'])): ?>
              <p><strong>Année :</strong> <?= htmlspecialchars($v['année']) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="mt-5">
    <a href="dashboard_client.php" class="btn btn-secondary shadow-sm">
      <i class="bi bi-arrow-left"></i> Retour au tableau de bord
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
