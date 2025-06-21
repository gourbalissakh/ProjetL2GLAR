<?php
session_start();
require_once '../Databases/db.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../Views/connexion.php");
    exit;
}

$id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Profil Admin - AutoPlus Garage</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Animate + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    :root {
      --gmi-color: rgb(115, 151, 187);
      --gmi-dark: rgb(88, 119, 149);
      --gmi-light: rgba(115, 151, 187, 0.15);
      --gmi-text: #34495e;
    }

    body {
      background: linear-gradient(to right, #f8fbfd, #e3ecf3);
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      background: linear-gradient(90deg, var(--gmi-dark), var(--gmi-color));
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .profile-card {
      background: white;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
      padding: 30px;
      max-width: 650px;
      margin: 40px auto;
      animation: fadeInUp 0.8s ease-in-out;
    }

    .profile-icon {
      font-size: 4.5rem;
      color: var(--gmi-color);
    }

    .profile-title {
      font-weight: 700;
      font-size: 1.6rem;
      color: var(--gmi-dark);
    }

    th {
      width: 35%;
      color: var(--gmi-color);
      font-weight: 600;
    }

    td {
      color: var(--gmi-text);
      font-weight: 500;
    }

    .badge-role {
      background-color: var(--gmi-dark);
      font-size: 0.9rem;
    }

    .btn-gmi {
      background-color: var(--gmi-color);
      color: white;
      border-radius: 10px;
    }

    .btn-gmi:hover {
      background-color: var(--gmi-dark);
    }

    footer {
      margin-top: 40px;
      text-align: center;
      color: #777;
      font-size: 0.85rem;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark px-3">
  <a class="navbar-brand text-white" href="dashboard_admin.php">
    <i class="bi bi-tools me-2"></i>AutoPlus Garage - Client
  </a>
  <div class="ms-auto">
    <a href="../Views/deconnexion.php" class="btn btn-outline-light rounded-pill px-3">
      <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
    </a>
  </div>
</nav>

<!-- Carte Profil -->
<div class="container">
  <div class="profile-card text-center animate__animated animate__fadeInUp">
    <i class="bi bi-person-circle profile-icon"></i>
    <h2 class="profile-title mt-2">Profil Client</h2>
    <p class="text-muted">Vos informations personnelles</p>

    <table class="table table-borderless text-start mt-4">
      <tr>
        <th><i class="bi bi-person-fill me-2"></i>Nom</th>
        <td><?= htmlspecialchars($admin['nom']) ?></td>
      </tr>
      <tr>
        <th><i class="bi bi-envelope-fill me-2"></i>Email</th>
        <td><?= htmlspecialchars($admin['email']) ?></td>
      </tr>
      <tr>
        <th><i class="bi bi-shield-lock-fill me-2"></i>Rôle</th>
        <td><span class="badge badge-role"><?= strtoupper($admin['role']) ?></span></td>
      </tr>
      <tr>
        <th><i class="bi bi-calendar-check-fill me-2"></i>Date</th>
        <td><?= date('d/m/Y H:i') ?></td>
      </tr>
    </table>

    
  </div>

  <div class="text-center mt-3">
    <button class="btn btn-secondary" onclick="window.history.back();">
      <i class="bi bi-arrow-left"></i> Retour
    </button>
  </div>
</div>

<footer>&copy; 2025 AutoPlus Garage - Tous droits réservés</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
