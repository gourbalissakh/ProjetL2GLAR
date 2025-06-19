<?php
session_start();
require_once '../Databases/db.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Views/connexion.php");
    exit;
}

$admin = $_SESSION['nom'] ?? 'Admin';

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'ajouter') {
        $id_reparation = $_POST['id_reparation'];
        $montant = $_POST['montant'];
        $date = $_POST['date_facture'];
        $stmt = $pdo->prepare("INSERT INTO facture (id_reparation, montant, date_facture) VALUES (?, ?, ?)");
        $stmt->execute([$id_reparation, $montant, $date]);
        header("Location: gestion_factures.php");
        exit;
    }

    if ($_POST['action'] === 'modifier') {
        $id = $_POST['id'];
        $montant = $_POST['montant'];
        $date = $_POST['date_facture'];
        $stmt = $pdo->prepare("UPDATE facture SET montant = ?, date_facture = ? WHERE id = ?");
        $stmt->execute([$montant, $date, $id]);
        header("Location: gestion_factures.php");
        exit;
    }

    if ($_POST['action'] === 'supprimer') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM facture WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: gestion_factures.php");
        exit;
    }
}

// Requêtes d'affichage
$factures = $pdo->query("
  SELECT f.*, v.immatriculation, v.marque
  FROM facture f
  JOIN reparation r ON f.id_reparation = r.id
  JOIN vehicule v ON r.id_vehicule = v.id
  ORDER BY f.date_facture DESC
")->fetchAll(PDO::FETCH_ASSOC);

$reparations = $pdo->query("
  SELECT r.id, v.immatriculation, v.marque
  FROM reparation r
  JOIN vehicule v ON r.id_vehicule = v.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des factures</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --gmi-primary: rgb(115, 151, 187);
      --gmi-dark: rgb(88, 119, 149);
      --gmi-border-radius: 10px;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }
    .gmi-navbar {
      background: linear-gradient(90deg, var(--gmi-dark), var(--gmi-primary));
      padding: 10px 20px;
      border-radius: var(--gmi-border-radius);
    }
    .gmi-navbar .navbar-brand {
      color: #fff;
      font-size: 1.2rem;
    }
    .btn-gmi-primary {
      background: var(--gmi-primary);
      color: white;
      border-radius: var(--gmi-border-radius);
    }
    .btn-gmi-primary:hover {
      background: var(--gmi-dark);
    }
    thead {
      background-color: var(--gmi-primary);
      color: white;
    }
  </style>
</head>
<body>
<!-- NAVBAR ADMIN GMI235 -->
<nav class="navbar navbar-expand-lg gmi-navbar mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white" href="dashboard_admin.php">
      <i class="bi bi-speedometer2"></i> AutoPlus - Admin
    </a>
    <div class="d-flex">
      <a href="profil_admin.php" class="btn btn-light btn-sm me-2">
        <i class="bi bi-person-circle"></i> Profil
      </a>
      <a href="../Views/deconnexion.php" class="btn btn-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>
</nav>

<div class="container">
  <h1 class="mb-4"><i class="bi bi-receipt"></i> Gestion des factures</h1>

  <button class="btn btn-gmi-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter une facture
  </button>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Réparation</th>
        <th>Date</th>
        <th>Montant</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($factures as $f): ?>
        <tr>
          <td><?= $f['id'] ?></td>
          <td><?= $f['marque'] ?> - <?= $f['immatriculation'] ?></td>
          <td><?= $f['date_facture'] ?></td>
          <td><?= number_format($f['montant'], 2, ',', ' ') ?> FCFA</td>
          <td>
            <!-- Modifier -->
            <button class="btn btn-warning btn-sm text-white me-1"
              data-bs-toggle="modal"
              data-bs-target="#modalModifier"
              data-id="<?= $f['id'] ?>"
              data-date="<?= $f['date_facture'] ?>"
              data-montant="<?= $f['montant'] ?>">
              <i class="bi bi-pencil"></i>
            </button>

            <!-- Supprimer -->
            <form method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
              <input type="hidden" name="action" value="supprimer">
              <input type="hidden" name="id" value="<?= $f['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
            </form>
                    <a href="imprimer_facture.php?id=<?= $f['id'] ?>" target="_blank" class="btn btn-success btn-sm">
        <i class="bi bi-printer"></i> Imprimer
        </a>
                </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="action" value="ajouter">
      <div class="modal-header"><h5 class="modal-title">Ajouter une facture</h5></div>
      <div class="modal-body">
        <label>Réparation</label>
        <select name="id_reparation" class="form-select mb-2" required>
          <option value="">-- Choisir --</option>
          <?php foreach ($reparations as $r): ?>
            <option value="<?= $r['id'] ?>"><?= $r['marque'] ?> - <?= $r['immatriculation'] ?></option>
          <?php endforeach; ?>
        </select>

        <label>Date</label>
        <input type="date" name="date_facture" class="form-control mb-2" required>

        <label>Montant</label>
        <input type="number" step="0.01" name="montant" class="form-control mb-2" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-gmi-primary">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="action" value="modifier">
      <input type="hidden" name="id" id="modifierId">
      <div class="modal-header"><h5 class="modal-title">Modifier la facture</h5></div>
      <div class="modal-body">
        <label>Date</label>
        <input type="date" name="date_facture" id="modifierDate" class="form-control mb-2" required>

        <label>Montant</label>
        <input type="number" step="0.01" name="montant" id="modifierMontant" class="form-control mb-2" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning text-white">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const modalModifier = document.getElementById('modalModifier');
modalModifier.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  modalModifier.querySelector('#modifierId').value = button.getAttribute('data-id');
  modalModifier.querySelector('#modifierDate').value = button.getAttribute('data-date');
  modalModifier.querySelector('#modifierMontant').value = button.getAttribute('data-montant');
});
</script>
</body>
</html>