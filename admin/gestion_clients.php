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
              $mot_de_passe =trim($_POST['mot_de_passe']);
           

            if ($nom &&  $prenom && $email && $mot_de_passe) {
               $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, 'client')");
                $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);

                header("Location: gestion_clients.php");
                exit;
            }
        }

        if ($action === 'modifier') {
            $id = (int) $_POST['id'];
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
       

            if ($id && $nom && $prenom && $email ) {
                $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? WHERE id = ? AND role = 'client'");
                $stmt->execute([$nom, $prenom, $email,  $id]);
                header("Location: gestion_clients.php");
                exit;
            }
        }

        if ($action === 'supprimer') {
            $id = (int) $_POST['id'];
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ? AND role = 'client'");
                $stmt->execute([$id]);
                header("Location: gestion_clients.php");
                exit;
            }
        }
    }
}

// Récupérer tous les clients
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE role = 'client' ORDER BY id DESC");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestion des clients - AutoPlus Garage</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
    :root {
      --gmi-primary: rgb(115, 151, 187);
      --gmi-primary-dark: rgb(88, 119, 149);
      --gmi-primary-light: rgba(115, 151, 187, 0.15);
      --gmi-text-light: #fff;
      --gmi-shadow: 0 4px 10px rgba(115, 151, 187, 0.3);
      --gmi-transition: 0.3s ease;
      --gmi-border-radius: 8px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }

    h1 {
      color: var(--gmi-primary-dark);
      margin-bottom: 20px;
      font-weight: 700;
    }

    .btn-gmi-primary {
      background-color: var(--gmi-primary);
      color: white;
      border-radius: var(--gmi-border-radius);
      transition: background-color var(--gmi-transition), box-shadow var(--gmi-transition);
    }
    .btn-gmi-primary:hover,
    .btn-gmi-primary:focus {
      background-color: var(--gmi-primary-dark);
      box-shadow: 0 0 10px var(--gmi-primary-dark);
      color: white;
    }

    table {
      background: white;
      border-radius: var(--gmi-border-radius);
      box-shadow: var(--gmi-shadow);
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
    </style>
</head>
<body>
 <nav class="navbar navbar-expand-lg gmi-navbar shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="dashboard_admin.php">
      <i class="bi bi-tools me-2"></i> AutoPlus Garage - Admin
    </a>

    <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav gap-2 align-items-center">
        <li class="nav-item">
          <a href="profil_admin.php" class="btn btn-light btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-person-circle"></i> Profil
          </a>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger btn-sm d-flex align-items-center gap-1" href="../Views/deconnexion.php">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<style>
/* Style GMI235 pour la navbar */
.gmi-navbar {
  background: linear-gradient(90deg, rgb(88, 119, 149), rgb(115, 151, 187));
  border-radius: 12px;
  padding: 10px 20px;
  font-family: 'Poppins', sans-serif;
}

.gmi-navbar .navbar-brand {
  font-size: 1.2rem;
  color: white !important;
}

.gmi-navbar .navbar-toggler {
  border: none;
}

.gmi-navbar .nav-link {
  color: white !important;
  font-weight: 500;
  transition: color 0.3s ease;
}

.gmi-navbar .nav-link:hover {
  color: #f8f9fa !important;
}

.gmi-navbar .btn {
  font-weight: 500;
  border-radius: 6px;
}

.gmi-navbar .btn-light:hover {
  background-color: #e2e6ea;
}
</style>


<div class="container">
  <h1><i class="bi bi-people-fill"></i> Gestion des clients</h1>

  <button class="btn btn-gmi-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAjouter">
    <i class="bi bi-plus-circle"></i> Ajouter un client
  </button>

  <table class="table table-striped table-hover shadow-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>PreNom</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($clients) > 0): ?>
        <?php foreach ($clients as $client): ?>
          <tr>
            <td><?= htmlspecialchars($client['id']) ?></td>
            <td><?= htmlspecialchars($client['nom']) ?></td>
             <td><?= htmlspecialchars($client['prenom']) ?></td>
            <td><?= htmlspecialchars($client['email']) ?></td>
            
            <td>
              <button
                class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#modalModifier"
                data-id="<?= $client['id'] ?>"
                data-nom="<?= htmlspecialchars($client['nom'], ENT_QUOTES) ?>"
                data-email="<?= htmlspecialchars($client['email'], ENT_QUOTES) ?>"
              >
                <i class="bi bi-pencil-square"></i> Modifier
              </button>

              <form method="POST" action="gestion_clients.php" class="d-inline" onsubmit="return confirm('Supprimer ce client ?');">
                <input type="hidden" name="action" value="supprimer" />
                <input type="hidden" name="id" value="<?= $client['id'] ?>" />
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i> Supprimer
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">Aucun client trouvé.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" action="gestion_clients.php" novalidate>
      <input type="hidden" name="action" value="ajouter" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalAjouterLabel">Ajouter un client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="ajouterNom" class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" id="ajouterNom" required />
        </div>
        <div class="mb-3">
          <label for="ajouterNom" class="form-label">Prenom</label>
          <input type="text" name="prenom" class="form-control" id="ajouterNom" required />
        </div>
        <div class="mb-3">
          <label for="ajouterEmail" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" id="ajouterEmail" required />
        </div>
        <div class="mb-3">
          <label for="ajouterEmail" class="form-label">Mot de passe</label>
          <input type="email" name="mot_de_passe" class="form-control" id="ajouterEmail" required />
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
    <form method="POST" class="modal-content" action="gestion_clients.php" novalidate>
      <input type="hidden" name="action" value="modifier" />
      <input type="hidden" name="id" id="modifierId" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalModifierLabel">Modifier un client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="modifierNom" class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" id="modifierNom" required />
        </div>
        <div class="mb-3">
          <label for="modifierNom" class="form-label">Prenom</label>
          <input type="text" name="prenom" class="form-control" id="modifierNom" required />
        </div>
        <div class="mb-3">
          <label for="modifierEmail" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" id="modifierEmail" required />
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

    const id = button.getAttribute('data-id');
    const nom = button.getAttribute('data-nom');
    const email = button.getAttribute('data-email');
    const tel = button.getAttribute('data-tel');

    modalModifier.querySelector('#modifierId').value = id;
    modalModifier.querySelector('#modifierNom').value = nom;
    modalModifier.querySelector('#modifierEmail').value = email;
    modalModifier.querySelector('#modifierTel').value = tel;
  });
</script>

</body>
</html>