<?php
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Views/connexion.php');
    exit;
}

require_once '../Databases/db.php';

$nom = $_SESSION['nom'] ?? 'Admin';

// Traitement POST : ajouter, modifier, supprimer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'ajouter') {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $mot_de_passe = trim($_POST['mot_de_passe']);

            if ($nom && $prenom && $email && $mot_de_passe) {
                $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, 'mecanicien')");
                $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);
                header("Location: gestion_mecaniciens.php");
                exit;
            }
        }

        if ($action === 'modifier') {
            $id = (int) $_POST['id'];
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);

            if ($id && $nom && $prenom && $email) {
                $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? WHERE id = ? AND role = 'mecanicien'");
                $stmt->execute([$nom, $prenom, $email, $id]);
                header("Location: gestion_mecaniciens.php");
                exit;
            }
        }

        if ($action === 'supprimer') {
            $id = (int) $_POST['id'];
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ? AND role = 'mecanicien'");
                $stmt->execute([$id]);
                header("Location: gestion_mecaniciens.php");
                exit;
            }
        }
    }
}

// Récupérer tous les mécaniciens
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE role = 'mecanicien' ORDER BY id DESC");
$stmt->execute();
$mecaniciens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des mécaniciens</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --gmi-primary: rgb(115, 151, 187);
      --gmi-primary-dark: rgb(88, 119, 149);
      --gmi-primary-light: rgba(115, 151, 187, 0.15);
      --gmi-text-light: #fff;
      --gmi-shadow: 0 4px 10px rgba(115, 151, 187, 0.3);
      --gmi-border-radius: 8px;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }
    .gmi-navbar {
      background: linear-gradient(90deg, var(--gmi-primary-dark), var(--gmi-primary));
      border-radius: 12px;
      padding: 10px 20px;
    }
    .gmi-navbar .navbar-brand { color: white !important; }
    .btn-gmi-primary {
      background-color: var(--gmi-primary);
      color: white;
      border-radius: var(--gmi-border-radius);
    }
    .btn-gmi-primary:hover {
      background-color: var(--gmi-primary-dark);
    }
    table {
      background: white;
      border-radius: var(--gmi-border-radius);
      box-shadow: var(--gmi-shadow);
    }
    thead {
      background-color: var(--gmi-primary);
      color: var(--gmi-text-light);
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg gmi-navbar mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard_admin.php">
      <i class="bi bi-tools me-2"></i> AutoPlus Garage - Admin
    </a>
    <div class="d-flex">
      <a href="profil_admin.php" class="btn btn-light btn-sm me-2"><i class="bi bi-person-circle"></i> Profil</a>
      <a href="../Views/deconnexion.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
  </div>
</nav>

<div class="container">
  <h1><i class="bi bi-wrench"></i> Gestion des mécaniciens</h1>

  <button class="btn btn-gmi-primary my-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter un mécanicien
  </button>

  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($mecaniciens as $meca): ?>
      <tr>
        <td><?= htmlspecialchars($meca['id']) ?></td>
        <td><?= htmlspecialchars($meca['nom']) ?></td>
        <td><?= htmlspecialchars($meca['prenom']) ?></td>
        <td><?= htmlspecialchars($meca['email']) ?></td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalModifier"
            data-id="<?= $meca['id'] ?>" data-nom="<?= htmlspecialchars($meca['nom'], ENT_QUOTES) ?>"
            data-prenom="<?= htmlspecialchars($meca['prenom'], ENT_QUOTES) ?>"
            data-email="<?= htmlspecialchars($meca['email'], ENT_QUOTES) ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <form method="POST" class="d-inline">
            <input type="hidden" name="action" value="supprimer">
            <input type="hidden" name="id" value="<?= $meca['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?');">
              <i class="bi bi-trash"></i>
            </button>
          </form>
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
      <div class="modal-header"><h5 class="modal-title">Ajouter un mécanicien</h5></div>
      <div class="modal-body">
        <input name="nom" class="form-control mb-2" placeholder="Nom" required>
        <input name="prenom" class="form-control mb-2" placeholder="Prénom" required>
        <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
        <input name="mot_de_passe" type="password" class="form-control mb-2" placeholder="Mot de passe" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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
      <div class="modal-header"><h5 class="modal-title">Modifier le mécanicien</h5></div>
      <div class="modal-body">
        <input name="nom" id="modifierNom" class="form-control mb-2" placeholder="Nom" required>
        <input name="prenom" id="modifierPrenom" class="form-control mb-2" placeholder="Prénom" required>
        <input name="email" type="email" id="modifierEmail" class="form-control mb-2" placeholder="Email" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-warning text-white">Modifier</button>
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
  modalModifier.querySelector('#modifierNom').value = button.getAttribute('data-nom');
  modalModifier.querySelector('#modifierPrenom').value = button.getAttribute('data-prenom');
  modalModifier.querySelector('#modifierEmail').value = button.getAttribute('data-email');
});
</script>

</body>
</html>
