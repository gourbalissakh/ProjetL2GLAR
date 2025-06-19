<?php
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Views/connexion.php');
    exit;
}

require_once '../Databases/db.php';

// Traitement POST (ajouter, modifier, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'ajouter') {
        $id_vehicule = (int) $_POST['id_vehicule'];
        $id_mecanicien = (int) $_POST['id_mecanicien'];
        $date_reparation = $_POST['date_reparation'];
        $description = trim($_POST['description']);

        if ($id_vehicule && $date_reparation && $description) {
            $stmt = $pdo->prepare("INSERT INTO reparation (id_vehicule, id_mecanicien, date_reparation, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_vehicule, $id_mecanicien ?: null, $date_reparation, $description]);
            header('Location: gestion_reparations.php');
            exit;
        }
    }

    if ($action === 'modifier') {
        $id = (int) $_POST['id'];
        $id_vehicule = (int) $_POST['id_vehicule'];
        $id_mecanicien = (int) $_POST['id_mecanicien'];
        $date_reparation = $_POST['date_reparation'];
        $description = trim($_POST['description']);

        if ($id && $id_vehicule && $date_reparation && $description) {
            $stmt = $pdo->prepare("UPDATE reparation SET id_vehicule = ?, id_mecanicien = ?, date_reparation = ?, description = ? WHERE id = ?");
            $stmt->execute([$id_vehicule, $id_mecanicien ?: null, $date_reparation, $description, $id]);
            header('Location: gestion_reparations.php');
            exit;
        }
    }

    if ($action === 'supprimer') {
        $id = (int) $_POST['id'];
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM reparation WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: gestion_reparations.php');
            exit;
        }
    }
}

// Récupérer toutes les réparations avec infos véhicules et mécaniciens
$stmt = $pdo->prepare("
    SELECT r.*, v.marque, v.immatriculation, 
           m.nom AS mecanicien_nom, m.prenom AS mecanicien_prenom 
    FROM reparation r
    JOIN vehicule v ON r.id_vehicule = v.id
    LEFT JOIN utilisateur m ON r.id_mecanicien = m.id AND m.role = 'mecanicien'
    ORDER BY r.id DESC
");
$stmt->execute();
$reparations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les véhicules pour le select
$stmt = $pdo->prepare("SELECT id, marque, immatriculation FROM vehicule ORDER BY marque");
$stmt->execute();
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les mécaniciens pour le select
$stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateur WHERE role = 'mecanicien' ORDER BY nom");
$stmt->execute();
$mecaniciens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des réparations - AutoPlus Garage</title>
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
      }
      body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        padding: 20px;
      }
      h1 {
        color: var(--gmi-primary-dark);
        font-weight: 700;
        margin-bottom: 20px;
      }
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
      tbody tr:hover {
        background-color: var(--gmi-primary-light);
        cursor: pointer;
      }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, var(--gmi-primary-dark), var(--gmi-primary)); border-radius: 12px; padding: 10px 20px; color: white; margin-bottom: 20px;">
  <a class="navbar-brand fw-bold" href="dashboard_admin.php">
    <i class="bi bi-tools me-2"></i> AutoPlus Garage - Admin
  </a>
  <div class="d-flex">
    <a href="profil_admin.php" class="btn btn-light btn-sm me-2"><i class="bi bi-person-circle"></i> Profil</a>
    <a href="../Views/deconnexion.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
  </div>
</nav>

<div class="container">
  <h1><i class="bi bi-gear-wide-connected"></i> Gestion des réparations</h1>

  <button class="btn btn-gmi-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter une réparation
  </button>

  <table class="table table-striped table-hover shadow-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>Véhicule</th>
        <th>Mécanicien</th>
        <th>Date</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($reparations) > 0): ?>
        <?php foreach ($reparations as $rep): ?>
          <tr>
            <td><?= htmlspecialchars($rep['id']) ?></td>
            <td><?= htmlspecialchars($rep['marque'] . ' - ' . $rep['immatriculation']) ?></td>
            <td><?= htmlspecialchars($rep['mecanicien_nom'] ? $rep['mecanicien_nom'].' '.$rep['mecanicien_prenom'] : 'Non assigné') ?></td>
            <td><?= htmlspecialchars($rep['date_reparation']) ?></td>
            <td><?= nl2br(htmlspecialchars($rep['description'])) ?></td>
            <td>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalModifier"
                data-id="<?= $rep['id'] ?>"
                data-id_vehicule="<?= $rep['id_vehicule'] ?>"
                data-id_mecanicien="<?= $rep['id_mecanicien'] ?>"
                data-date_reparation="<?= $rep['date_reparation'] ?>"
                data-description="<?= htmlspecialchars($rep['description'], ENT_QUOTES) ?>"
              >
                <i class="bi bi-pencil"></i>
              </button>
              <form method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                <input type="hidden" name="action" value="supprimer" />
                <input type="hidden" name="id" value="<?= $rep['id'] ?>" />
                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">Aucune réparation trouvée.</td></tr>
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
        <h5 class="modal-title" id="modalAjouterLabel">Ajouter une réparation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="ajouterVehicule" class="form-label">Véhicule</label>
          <select name="id_vehicule" id="ajouterVehicule" class="form-select" required>
            <option value="">-- Sélectionner un véhicule --</option>
            <?php foreach ($vehicules as $vehicule): ?>
              <option value="<?= $vehicule['id'] ?>"><?= htmlspecialchars($vehicule['marque'] . ' - ' . $vehicule['immatriculation']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="ajouterMecanicien" class="form-label">Mécanicien</label>
          <select name="id_mecanicien" id="ajouterMecanicien" class="form-select">
            <option value="">-- Non assigné --</option>
            <?php foreach ($mecaniciens as $meca): ?>
              <option value="<?= $meca['id'] ?>"><?= htmlspecialchars($meca['nom'] . ' ' . $meca['prenom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="ajouterDate" class="form-label">Date de réparation</label>
          <input type="date" name="date_reparation" id="ajouterDate" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="ajouterDescription" class="form-label">Description</label>
          <textarea name="description" id="ajouterDescription" class="form-control" rows="3" required></textarea>
        </div>
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
    <form method="POST" class="modal-content" novalidate>
      <input type="hidden" name="action" value="modifier" />
      <input type="hidden" name="id" id="modifierId" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalModifierLabel">Modifier la réparation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="modifierVehicule" class="form-label">Véhicule</label>
          <select name="id_vehicule" id="modifierVehicule" class="form-select" required>
            <?php foreach ($vehicules as $vehicule): ?>
              <option value="<?= $vehicule['id'] ?>"><?= htmlspecialchars($vehicule['marque'] . ' - ' . $vehicule['immatriculation']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="modifierMecanicien" class="form-label">Mécanicien</label>
          <select name="id_mecanicien" id="modifierMecanicien" class="form-select">
            <option value="">-- Non assigné --</option>
            <?php foreach ($mecaniciens as $meca): ?>
              <option value="<?= $meca['id'] ?>"><?= htmlspecialchars($meca['nom'] . ' ' . $meca['prenom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="modifierDate" class="form-label">Date de réparation</label>
          <input type="date" name="date_reparation" id="modifierDate" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="modifierDescription" class="form-label">Description</label>
          <textarea name="description" id="modifierDescription" class="form-control" rows="3" required></textarea>
        </div>
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
    modalModifier.querySelector('#modifierVehicule').value = button.getAttribute('data-id_vehicule');
    modalModifier.querySelector('#modifierMecanicien').value = button.getAttribute('data-id_mecanicien') || '';
    modalModifier.querySelector('#modifierDate').value = button.getAttribute('data-date_reparation');
    modalModifier.querySelector('#modifierDescription').value = button.getAttribute('data-description');
  });
</script>

</body>
</html>
