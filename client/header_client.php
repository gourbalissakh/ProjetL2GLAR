<?php


// Exemple simple : si le client n’est pas connecté, redirection
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Views/connexion.php');
    exit;
}

// Récupérer le nom du client depuis la session ou la base de données
$nomClient = $_SESSION['nom'] ?? 'Client';
$prenomClient = $_SESSION['prenom'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Header AutoPlus</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      --gmi-primary: rgb(115, 151, 187);
      --gmi-primary-dark: rgb(88, 119, 149);
      --gmi-primary-light: rgba(115, 151, 187, 0.15);
      --gmi-text-light: #fff;
      --gmi-shadow: 0 4px 12px rgba(115, 151, 187, 0.4);
      --gmi-transition: 0.3s ease;
      --gmi-border-radius: 8px;
      --gmi-btn-padding: 0.35rem 0.8rem;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .gmi-header {
      background: linear-gradient(90deg, var(--gmi-primary-dark), var(--gmi-primary));
      box-shadow: var(--gmi-shadow);
      padding: 12px 0;
      user-select: none;
    }

    .gmi-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: var(--gmi-text-light);
      font-weight: 700;
      font-size: 1.6rem;
      gap: 10px;
      transition: transform var(--gmi-transition);
    }
    .gmi-logo img {
      height: 45px;
      filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.2));
      border-radius: 6px;
      transition: transform var(--gmi-transition);
    }
    .gmi-logo:hover,
    .gmi-logo:focus {
      text-decoration: none;
      transform: scale(1.05);
    }
    .gmi-logo:hover img,
    .gmi-logo:focus img {
      transform: scale(1.15);
    }

    .gmi-admin-name {
      color: var(--gmi-text-light);
      font-weight: 600;
      font-size: 1.1rem;
      white-space: nowrap;
      flex-grow: 1;
      text-align: center;
      user-select: text;
    }

    .gmi-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .gmi-actions a.btn {
      padding: var(--gmi-btn-padding);
      font-size: 0.9rem;
      border-radius: var(--gmi-border-radius);
      display: flex;
      align-items: center;
      gap: 6px;
      transition: background-color var(--gmi-transition), box-shadow var(--gmi-transition);
      user-select: none;
    }

    .btn-primary {
      background-color: var(--gmi-primary-light);
      border: none;
      color: var(--gmi-primary-dark);
      font-weight: 600;
    }
    .btn-primary:hover,
    .btn-primary:focus {
      background-color: var(--gmi-text-light);
      color: var(--gmi-primary-dark);
      box-shadow: 0 0 8px var(--gmi-text-light);
    }

    .logout-link {
      background-color: #ff5c5c;
      border: none;
      color: #fff;
      font-weight: 600;
    }
    .logout-link:hover,
    .logout-link:focus {
      background-color: #e04444;
      box-shadow: 0 0 8px #e04444;
      color: #fff;
    }

    /* Responsive */
    @media (max-width: 576px) {
      .gmi-header .container {
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
      }
      .gmi-admin-name {
        flex-basis: 100%;
        text-align: center;
        order: 3;
      }
      .gmi-actions {
        order: 2;
        gap: 8px;
      }
      .gmi-logo {
        order: 1;
        justify-content: center;
        width: 100%;
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

<header class="gmi-header shadow-sm">
  <div class="container d-flex align-items-center justify-content-between flex-nowrap">
    <!-- Logo -->
    <a href="dashboard_client.php" class="gmi-logo" aria-label="Accueil AutoPlus Garage">
      <img src="../images/logooo.png" alt="Logo AutoPlus" />
      AutoPlus Garage
    </a>

    <!-- Profil client -->
    <div class="gmi-admin-name" aria-live="polite" aria-atomic="true">
      Bonjour, <strong><?= htmlspecialchars($nomClient . ' ' . $prenomClient) ?></strong>
    </div>


    <!-- Actions Profil & Déconnexion -->
    <div class="gmi-actions">
      <a href="profil_client.php" class="btn btn-primary" title="Voir le profil">
        <i class="bi bi-person-circle"></i> Profil
      </a>
      <a href="../Views/deconnexion.php" class="logout-link btn" title="Se déconnecter">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>
</header>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
