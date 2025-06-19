<?php
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Views/connexion.php');
    exit;
}

require_once '../Databases/db.php';

$nom = $_SESSION['nom'] ?? 'Admin';

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'ajouter') {
        $marque = trim($_POST['marque']);
        $immatriculation = trim($_POST['immatriculation']);
        $id_utilisateur = (int) $_POST['id_utilisateur'];
        if ($marque && $immatriculation && $id_utilisateur) {
            $stmt = $pdo->prepare("INSERT INTO vehicule (marque, immatriculation, id_utilisateur) VALUES (?, ?, ?)");
            $stmt->execute([$marque, $immatriculation, $id_utilisateur]);
            header("Location: gestion_vehicules.php");
            exit;
        }
    } elseif ($action === 'modifier') {
        $id = (int) $_POST['id'];
        $marque = trim($_POST['marque']);
        $immatriculation = trim($_POST['immatriculation']);
        $id_utilisateur = (int) $_POST['id_utilisateur'];
        if ($id && $marque && $immatriculation && $id_utilisateur) {
            $stmt = $pdo->prepare("UPDATE vehicule SET marque = ?, immatriculation = ?, id_utilisateur = ? WHERE id = ?");
            $stmt->execute([$marque, $immatriculation, $id_utilisateur, $id]);
            header("Location: gestion_vehicules.php");
            exit;
        }
    } elseif ($action === 'supprimer') {
        $id = (int) $_POST['id'];
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM vehicule WHERE id = ?");
                $stmt->execute([$id]);
                header("Location: gestion_vehicules.php");
                exit;
            } catch (PDOException $e) {
                $erreur = "Impossible de supprimer ce véhicule. Il est lié à d'autres données.";
            }
        }
    }
}

// Récupérer les véhicules avec client
$stmt = $pdo->query("
    SELECT v.*, u.nom AS nom_client, u.prenom AS prenom_client
    FROM vehicule v
    JOIN utilisateur u ON v.id_utilisateur = u.id
    ORDER BY v.id DESC
");
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les clients
$stmtClients = $pdo->query("SELECT id, nom, prenom FROM utilisateur WHERE role = 'client'");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);




// Récupérer les véhicules
$stmt = $pdo->query("SELECT v.*, u.nom AS nom_client, u.prenom AS prenom_client
                     FROM vehicule v
                     JOIN utilisateur u ON v.id_utilisateur = u.id
                     ORDER BY v.id DESC");
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les clients pour le formulaire
$clients = $pdo->query("SELECT id, nom, prenom FROM utilisateur WHERE role = 'client'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gestion des véhicules</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root {
      --gmi-primary: rgb(115, 151, 187);
      --gmi-primary-dark: rgb(88, 119, 149);
      --gmi-text-light: #fff;
      --gmi-border-radius: 10px;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }
    .gmi-navbar {
      background: linear-gradient(90deg, var(--gmi-primary-dark), var(--gmi-primary));
      border-radius: var(--gmi-border-radius);
      padding: 10px 20px;
    }
    .gmi-navbar .navbar-brand, .gmi-navbar .btn {
      color: white !important;
      font-weight: bold;
    }
    .btn-gmi-primary {
      background-color: var(--gmi-primary);
      border: none;
      color: white;
      border-radius: var(--gmi-border-radius);
    }
    .btn-gmi-primary:hover {
      background-color: var(--gmi-primary-dark);
    }
    table {
      background: white;
      border-radius: var(--gmi-border-radius);
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(115, 151, 187, 0.2);
    }
    thead {
      background-color: var(--gmi-primary);
      color: white;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg gmi-navbar mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard_admin.php"><i class="bi bi-car-front-fill"></i> AutoPlus - Admin</a>
    <div class="d-flex">
      <a href="profil_admin.php" class="btn btn-gmi-primary btn-sm me-2"><i class="bi bi-person-circle"></i> Profil</a>
      <a href="../Views/deconnexion.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
  </div>
</nav>

<div class="container">
  <h1><i class="bi bi-car-front-fill"></i> Gestion des véhicules</h1>

  <button class="btn btn-gmi-primary my-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter un véhicule
  </button>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Marque</th>
        <th>Immatriculation</th>
        <th>Client</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($vehicules as $v): ?>
        <tr>
          <td><?= $v['id'] ?></td>
          <td><?= htmlspecialchars($v['marque']) ?></td>
          <td><?= htmlspecialchars($v['immatriculation']) ?></td>
          <td><?= htmlspecialchars($v['nom_client'] . ' ' . $v['prenom_client']) ?></td>
          <td>
  <!-- Bouton Modifier -->
  <button class="btn btn-warning btn-sm me-1"
          data-bs-toggle="modal"
          data-bs-target="#modalModifier"
          data-id="<?= $v['id'] ?>"
          data-marque="<?= htmlspecialchars($v['marque'], ENT_QUOTES) ?>"
          data-immatriculation="<?= htmlspecialchars($v['immatriculation'], ENT_QUOTES) ?>"
          data-id_utilisateur="<?= $v['id_utilisateur'] ?>">
    <i class="bi bi-pencil"></i>
  </button>

  <!-- Bouton Supprimer -->
  <form method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce véhicule ?')">
    <input type="hidden" name="action" value="supprimer">
    <input type="hidden" name="id" value="<?= $v['id'] ?>">
    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
  </form>
</td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="action" value="modifier">
      <input type="hidden" name="id" id="modifierId" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalModifierLabel">Modifier le véhicule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input name="marque" id="modifierMarque" class="form-control mb-2" placeholder="Marque" required>
        <input name="immatriculation" id="modifierImmatriculation" class="form-control mb-2" placeholder="Immatriculation" required>
        <select name="id_utilisateur" id="modifierClient" class="form-select mb-2" required>
          <option value="">-- Sélectionner un client --</option>
          <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-warning text-white">Modifier</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="action" value="ajouter" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalAjouterLabel">Ajouter un véhicule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input name="marque" class="form-control mb-2" placeholder="Marque" required>
        <input name="immatriculation" class="form-control mb-2" placeholder="Immatriculation" required>
        <select name="id_utilisateur" class="form-select mb-2" required>
          <option value="">-- Sélectionner un client --</option>
          <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-gmi-primary">Ajouter</button>
      </div>
    </form>
  </div>
</div>
<script>
  const modalModifier = document.getElementById('modalModifier');
  modalModifier.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    modalModifier.querySelector('#modifierId').value = button.getAttribute('data-id');
    modalModifier.querySelector('#modifierMarque').value = button.getAttribute('data-marque');
    modalModifier.querySelector('#modifierImmatriculation').value = button.getAttribute('data-immatriculation');
    modalModifier.querySelector('#modifierClient').value = button.getAttribute('data-id_utilisateur');
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
