<?php
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Views/connexion.php');
    exit;
}

require_once '../Databases/db.php';

$nom = $_SESSION['nom'] ?? 'Admin';

// Requêtes sécurisées pour récupérer les stats, renvoyer 0 si null
function fetchCount($pdo, $query) {
    $stmt = $pdo->query($query);
    $count = $stmt->fetchColumn();
    return $count !== false ? (int)$count : 0;
}
// Nombre de diagnostics
$stmt = $pdo->query("SELECT COUNT(*) FROM diagnostic");
$nb_diagnostics = $stmt->fetchColumn();

// Nombre de factures
$stmt = $pdo->query("SELECT COUNT(*) FROM facture");
$nb_factures = $stmt->fetchColumn();

$nb_clients = fetchCount($pdo, "SELECT COUNT(*) FROM utilisateur WHERE role = 'client'");
$nb_vehicules = fetchCount($pdo, "SELECT COUNT(*) FROM vehicule");
$nb_reparations = fetchCount($pdo, "
    SELECT COUNT(*) FROM (
        SELECT DISTINCT id_vehicule, date_reparation
        FROM reparation
    ) AS rep_uniques
");
$nb_mecaniciens = fetchCount($pdo, "SELECT COUNT(*) FROM utilisateur WHERE role = 'mecanicien'");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Garage AutoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f5f9ff, #e2ecf5);
      font-family: 'Segoe UI', sans-serif;
    }
    .hero {
      text-align: center;
      padding: 50px 20px 20px;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #0056b3;
    }
    .hero p {
      font-size: 1.1rem;
      color: #666;
    }
    .dashboard-cards {
      padding: 40px 20px;
    }
    .card-box {
      border: none;
      border-radius: 20px;
      color: white;
      padding: 30px 20px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card-box:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .card-box i {
      font-size: 3rem;
      margin-bottom: 15px;
    }
    .footer {
      text-align: center;
      padding: 20px;
      background: #7397bb;
      color: white;
      margin-top: 50px;
    }

    /* Couleurs GMI235 */
    .bg-gradient-blue     { background: linear-gradient(135deg, #007bff, #00c6ff); }
    .bg-gradient-green    { background: linear-gradient(135deg, #28a745, #6fdc8c); }
    .bg-gradient-purple   { background: linear-gradient(135deg, #6f42c1, #b58ded); }
    .bg-gradient-orange   { background: linear-gradient(135deg, #ff6f00, #ffa733); }
    .bg-gradient-cyan     { background: linear-gradient(135deg, #1ecbe1, #147d9f); }
    .bg-gradient-dark     { background: linear-gradient(135deg, #2c3e50, #34495e); }

    .hover-zoom {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-zoom:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }
  </style>
</head>
<body>

<?php include 'header_admin.php'; ?>

<!-- Section Accueil -->
<div class="container my-5 text-center">
  <img src="../images/logooo.png" alt="Logo Garage AutoPlus" class="img-fluid mb-4" style="max-width: 120px;">
  <h1 class="fw-bold text-primary">Bienvenue, <?= htmlspecialchars($nom) ?></h1>
  <p class="lead text-muted"><strong>Gérez votre garage, les clients, véhicules et réparations en un clic.</strong></p>
</div>

<!-- Cartes -->
<div class="container dashboard-cards">
  <div class="row g-4">

    <!-- Clients -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-blue border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-people-fill display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_clients ?> Clients</h5>
          <p>Voir la liste des clients enregistrés.</p>
          <a href="gestion_clients.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

    <!-- Véhicules -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-green border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-car-front-fill display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_vehicules ?> Véhicules</h5>
          <p>Consulter les véhicules pris en charge.</p>
          <a href="gestion_vehicules.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

    <!-- Réparations -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-purple border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-tools display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_reparations ?> Réparations</h5>
          <p>Suivre toutes les réparations en cours.</p>
          <a href="gestion_reparations.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

    <!-- Mécaniciens -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-orange border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-person-badge-fill display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_mecaniciens ?> Mécaniciens</h5>
          <p>Gérez vos techniciens professionnels.</p>
          <a href="gestion_mecaniciens.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

    <!-- Diagnostics -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-cyan border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-heart-pulse-fill display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_diagnostics ?> Diagnostics</h5>
          <p>Consulter les diagnostics effectués.</p>
          <a href="gestion_diagnostics.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

    <!-- Factures -->
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 text-white bg-gradient-dark border-0 shadow-lg hover-zoom">
        <div class="card-body text-center">
          <i class="bi bi-receipt-cutoff display-4 mb-3"></i>
          <h5 class="card-title fw-bold"><?= $nb_factures ?> Factures</h5>
          <p>Gérez les paiements et documents officiels.</p>
          <a href="gestion_factures.php" class="btn btn-light btn-sm">Gérer</a>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="footer">
  &copy; <?= date("Y") ?> Garage AutoPlus - Admin Dashboard
</div>

</body>
</html>
