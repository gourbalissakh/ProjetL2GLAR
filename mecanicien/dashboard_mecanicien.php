<?php
session_start();
require_once '../Databases/db.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'mecanicien') {
    header('Location: ../Views/connexion.php');
    exit;
}

$mecanicienId = $_SESSION['utilisateur_id'];
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_id'])) {
    $idRep = (int)$_POST['confirmer_id'];
    $stmt = $pdo->prepare("UPDATE reparation SET statut = 'confirmée' WHERE id = ? AND id_mecanicien = ?");
    $stmt->execute([$idRep, $mecanicienId]);
    header("Location: dashboard_mecanicien.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.id, r.date_reparation, r.description, v.marque, v.immatriculation
    FROM reparation r
    JOIN vehicule v ON r.id_vehicule = v.id
    WHERE r.id_mecanicien = ? AND r.statut = 'en_cours'
    ORDER BY r.date_reparation DESC
");
$stmt->execute([$mecanicienId]);
$reparationsEnCours = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT r.id, r.date_reparation, r.description, v.marque, v.immatriculation
    FROM reparation r
    JOIN vehicule v ON r.id_vehicule = v.id
    WHERE r.id_mecanicien = ? AND r.statut = 'confirmée'
    ORDER BY r.date_reparation DESC
");
$stmt->execute([$mecanicienId]);
$reparationsConfirmees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalEnCours = count($reparationsEnCours);
$totalConfirmées = count($reparationsConfirmees);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Dashboard Mécanicien - Garage AutoPlus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
  /* Style GMI235 */
  body {
    background: #e3ebf9;
    font-family: 'Poppins', sans-serif;
    padding: 20px;
  }
  nav.navbar {
    background: linear-gradient(90deg, rgb(115,151,187), rgb(90,130,170));
    box-shadow: 0 3px 12px rgb(115 151 187 / 0.5);
  }
  nav.navbar a.navbar-brand {
    color: #f0f4fa;
    font-weight: 700;
    font-size: 1.5rem;
  }
  nav.navbar a.navbar-brand i {
    margin-right: 8px;
  }
  nav.navbar div > span {
    color: #f0f4fa;
    font-weight: 600;
  }
  nav.navbar a.btn-outline-danger {
    border-color: #f0f4fa;
    color: #f0f4fa;
    transition: 0.3s;
  }
  nav.navbar a.btn-outline-danger:hover {
    background: #f0f4fa;
    color: rgb(115,151,187);
  }

  h1 {
    color: rgb(60,90,130);
    font-weight: 700;
    margin-bottom: 30px;
    text-shadow: 0 0 4px rgb(115,151,187);
  }

  .stat-card {
    border-radius: 15px;
    color: white;
    padding: 30px 20px;
    box-shadow: 0 8px 20px rgb(115 151 187 / 0.35);
    cursor: default;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgb(115 151 187 / 0.6);
  }
  .stat-icon {
    font-size: 3.5rem;
    opacity: 0.85;
  }
  .stat-blue {
    background: linear-gradient(135deg, rgb(115,151,187), rgb(90,130,170));
  }
  .stat-green {
    background: linear-gradient(135deg, rgb(100,180,140), rgb(60,140,100));
  }

  /* List group for repairs */
  .list-group-item {
    border-radius: 12px;
    margin-bottom: 15px;
    box-shadow: 0 6px 18px rgb(115 151 187 / 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .list-group-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgb(115 151 187 / 0.4);
  }

  .btn-confirm {
    background: rgb(100,180,140);
    border: none;
    color: white;
    font-weight: 600;
    transition: background 0.3s ease;
  }
  .btn-confirm:hover {
    background: rgb(60,140,100);
  }

</style>
</head>
<body>


<div class="container">

  <h1>Bonjour, <?= htmlspecialchars($prenom) ?> !</h1>

  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="stat-card stat-blue text-center">
        <i class="bi bi-clock-history stat-icon"></i>
        <h3 class="mt-3"><?= $totalEnCours ?></h3>
        <p>Réparations en cours</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="stat-card stat-green text-center">
        <i class="bi bi-check2-square stat-icon"></i>
        <h3 class="mt-3"><?= $totalConfirmées ?></h3>
        <p>Réparations confirmées</p>
      </div>
    </div>
  </div>

  <h2 class="mb-3">Réparations à traiter</h2>
  <?php if (empty($reparationsEnCours)): ?>
    <div class="alert alert-info">Vous n’avez aucune réparation en cours.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($reparationsEnCours as $r): ?>
        <div class="list-group-item d-flex justify-content-between align-items-start">
          <div>
            <h5><?= htmlspecialchars($r['marque']) ?> - <?= htmlspecialchars($r['immatriculation']) ?></h5>
            <small class="text-muted">Date : <?= htmlspecialchars($r['date_reparation']) ?></small>
            <p class="mb-1"><?= htmlspecialchars(substr($r['description'], 0, 100)) ?><?= strlen($r['description']) > 100 ? '...' : '' ?></p>
          </div>
          <form method="POST" class="ms-3">
            <input type="hidden" name="confirmer_id" value="<?= $r['id'] ?>">
            <button type="submit" class="btn btn-confirm btn-sm" title="Confirmer la réparation">
              <i class="bi bi-check-circle"></i> Confirmer
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <h2 class="mt-5 mb-3">Réparations confirmées</h2>
  <?php if (empty($reparationsConfirmees)): ?>
    <div class="alert alert-warning">Aucune réparation confirmée pour le moment.</div>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($reparationsConfirmees as $r): ?>
        <li class="list-group-item">
          <h5><?= htmlspecialchars($r['marque']) ?> - <?= htmlspecialchars($r['immatriculation']) ?></h5>
          <small class="text-muted">Date : <?= htmlspecialchars($r['date_reparation']) ?></small>
          <p><?= htmlspecialchars(substr($r['description'], 0, 150)) ?><?= strlen($r['description']) > 150 ? '...' : '' ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
