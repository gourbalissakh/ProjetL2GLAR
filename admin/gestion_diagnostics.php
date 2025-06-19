<?php
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Views/connexion.php');
    exit;
}

require_once '../Databases/db.php';

// Traitement POST (ajouter, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'ajouter') {
        $id_vehicule = (int) $_POST['id_vehicule'];
        $date_diagnostic = $_POST['date_diagnostic'];
        $description = trim($_POST['description']);

        if ($id_vehicule && $date_diagnostic && $description) {
            $stmt = $pdo->prepare("INSERT INTO diagnostic (id_vehicule, date_diagnostic, description) VALUES (?, ?, ?)");
            $stmt->execute([$id_vehicule, $date_diagnostic, $description]);
            header("Location: gestion_diagnostics.php");
            exit;
        }
    } elseif ($_POST['action'] === 'supprimer') {
        $id = (int) $_POST['id'];
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM diagnostic WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: gestion_diagnostics.php");
            exit;
        }
    }
    elseif ($_POST['action'] === 'modifier') {
    $id = (int) $_POST['id'];
    $date = $_POST['date_diagnostic'];
    $desc = trim($_POST['description']);
    if ($id && $date && $desc) {
        $stmt = $pdo->prepare("UPDATE diagnostic SET date_diagnostic = ?, description = ? WHERE id = ?");
        $stmt->execute([$date, $desc, $id]);
        header("Location: gestion_diagnostics.php");
        exit;
    }
}

}

// Récupérer tous les diagnostics avec info véhicule et client
$stmt = $pdo->query("
    SELECT d.id, d.date_diagnostic, d.description, v.marque, v.immatriculation, c.nom, c.prenom
    FROM diagnostic d
    JOIN vehicule v ON d.id_vehicule = v.id
    JOIN utilisateur c ON v.id_utilisateur = c.id
    ORDER BY d.date_diagnostic DESC
");
$diagnostics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les véhicules pour le formulaire d'ajout
$vehiculesStmt = $pdo->query("
    SELECT v.id, v.marque, v.immatriculation, c.nom, c.prenom
    FROM vehicule v
    JOIN utilisateur c ON v.id_utilisateur = c.id
    ORDER BY v.marque
");
$vehicules = $vehiculesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gestion des diagnostics - AutoPlus Garage</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
:root {
  --gmi-primary: rgb(115, 151, 187);
  --gmi-primary-dark: rgb(88, 119, 149);
  --gmi-primary-light: rgba(115, 151, 187, 0.15);
  --gmi-text-light: #fff;
  --gmi-shadow: 0 4px 10px rgba(115, 151, 187, 0.3);
  --gmi-border-radius: 8px;
  --gmi-transition: 0.3s ease;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #f8f9fa;
  padding: 20px;
}

h1 {
  color: var(--gmi-primary-dark);
  font-weight: 700;
  margin-bottom: 30px;
  text-align: center;
}

.btn-gmi-primary {
  background-color: var(--gmi-primary);
  color: var(--gmi-text-light);
  border-radius: var(--gmi-border-radius);
  transition: background-color var(--gmi-transition), box-shadow var(--gmi-transition);
}
.btn-gmi-primary:hover, .btn-gmi-primary:focus {
  background-color: var(--gmi-primary-dark);
  box-shadow: 0 0 10px var(--gmi-primary-dark);
  color: var(--gmi-text-light);
}

table {
  background: white;
  border-radius: var(--gmi-border-radius);
  box-shadow: var(--gmi-shadow);
  overflow: hidden;
}

thead {
  background-color: var(--gmi-primary);
  color: var(--gmi-text-light);
  font-weight: 600;
}

tbody tr:hover {
  background-color: var(--gmi-primary-light);
  cursor: pointer;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.85rem;
}

.modal-header {
  background-color: var(--gmi-primary);
  color: var(--gmi-text-light);
  border-bottom: none;
  border-top-left-radius: var(--gmi-border-radius);
  border-top-right-radius: var(--gmi-border-radius);
}

.modal-footer {
  border-top: none;
}

.form-control, .form-select {
  border-radius: var(--gmi-border-radius);
}

</style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, var(--gmi-primary-dark), var(--gmi-primary)); border-radius: var(--gmi-border-radius); margin-bottom: 30px;">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="dashboard_admin.php">
      <i class="bi bi-clipboard-data me-2"></i> AutoPlus Garage - Admin
    </a>
    <div class="d-flex gap-2">
      <a href="profil_admin.php" class="btn btn-light btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-person-circle"></i> Profil
      </a>
      <a href="../Views/deconnexion.php" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>
</nav>

<div class="container">
  <h1><i class="bi bi-clipboard-data"></i> Gestion des diagnostics</h1>

  <button class="btn btn-gmi-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter un diagnostic
  </button>

  <table class="table table-striped table-hover shadow-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Véhicule</th>
        <th>Client</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($diagnostics) > 0): ?>
        <?php foreach ($diagnostics as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['id']) ?></td>
            <td><?= htmlspecialchars($d['date_diagnostic']) ?></td>
            <td><?= htmlspecialchars($d['marque'] . ' - ' . $d['immatriculation']) ?></td>
            <td><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></td>
            <td><?= nl2br(htmlspecialchars($d['description'])) ?></td>
            <td>
              <form method="POST" onsubmit="return confirm('Supprimer ce diagnostic ?');" class="d-inline">
                <input type="hidden" name="action" value="supprimer" />
                <input type="hidden" name="id" value="<?= $d['id'] ?>" />
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i> Supprimer
                </button>

              </form>
              <!-- BTN MODIFIER -->
                <button class="btn btn-sm btn-warning text-white me-1"
                data-bs-toggle="modal"
                data-bs-target="#modalModifier"
                data-id="<?= $d['id'] ?>"
                data-vehicule="<?= $d['marque'] . ' - ' . $d['immatriculation'] ?>"
                data-idvehicule="<?= $d['id'] ?>"
                data-date="<?= $d['date_diagnostic'] ?>"
                data-description="<?= htmlspecialchars($d['description'], ENT_QUOTES) ?>">
                <i class="bi bi-pencil-square"></i>
                </button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">Aucun diagnostic trouvé.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" novalidate>
      <input type="hidden" name="action" value="ajouter" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalAjouterLabel">Ajouter un diagnostic</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <label for="id_vehicule" class="form-label">Véhicule</label>
        <select name="id_vehicule" id="id_vehicule" class="form-select" required>
          <option value="">-- Sélectionner un véhicule --</option>
          <?php foreach ($vehicules as $v): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['marque'] . ' - ' . $v['immatriculation'] . ' (' . $v['nom'] . ' ' . $v['prenom'] . ')') ?></option>
          <?php endforeach; ?>
        </select>

        <label for="date_diagnostic" class="form-label mt-3">Date du diagnostic</label>
        <input type="date" name="date_diagnostic" id="date_diagnostic" class="form-control" required max="<?= date('Y-m-d') ?>" />

        <label for="description" class="form-label mt-3">Description</label>
        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Description détaillée..." required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-gmi-primary">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="action" value="modifier" />
      <input type="hidden" name="id" id="modifierId" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalModifierLabel">Modifier le diagnostic</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <label for="modifierDate" class="form-label">Date du diagnostic</label>
        <input type="date" name="date_diagnostic" id="modifierDate" class="form-control" required>

        <label for="modifierDescription" class="form-label mt-3">Description</label>
        <textarea name="description" id="modifierDescription" rows="4" class="form-control" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-warning text-white">Enregistrer</button>
      </div>
    </form>
  </div>
</div>
<script>
const modalModifier = document.getElementById('modalModifier');
modalModifier.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  modalModifier.querySelector('#modifierId').value = button.getAttribute('data-id');
  modalModifier.querySelector('#modifierDate').value = button.getAttribute('data-date');
  modalModifier.querySelector('#modifierDescription').value = button.getAttribute('data-description');
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
