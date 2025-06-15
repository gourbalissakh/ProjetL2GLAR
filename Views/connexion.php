<?php
// connexion.php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Garage AutoPlus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(to right, rgb(115, 151, 187), #f8f9fa);
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      width: 100%;
      max-width: 400px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .card-header {
      background-color: rgb(115, 151, 187);
      color: white;
      text-align: center;
      padding: 30px;
    }

    .card-header i {
      font-size: 3rem;
      margin-bottom: 10px;
    }

    .form-control:focus {
      border-color: rgb(115, 151, 187);
      box-shadow: 0 0 0 0.2rem rgba(115, 151, 187, 0.25);
    }

    .btn-primary {
      background-color: rgb(115, 151, 187);
      border: none;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #0d6efd;
      transform: scale(1.03);
    }

    .text-small {
      font-size: 0.9rem;
    }

    .footer-link {
      color: rgb(115, 151, 187);
      text-decoration: none;
    }

    .footer-link:hover {
      text-decoration: underline;
      color: #0d6efd;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="card-header">
      <i class="bi bi-person-circle"></i>
      <h3>Connexion</h3>
    </div>
    <div class="card-body p-4">
      <form method="POST" action="traitement_connexion.php">
        <div class="mb-3">
          <label for="email" class="form-label">Adresse e-mail</label>
          <input type="email" class="form-control" id="email" name="email" required placeholder="exemple@mail.com">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Votre mot de passe">
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
      </form>
    </div>
    <div class="text-center p-3 text-small">
      Vous n'avez pas encore de compte ? <a href="inscription.php" class="footer-link">Inscrivez-vous</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
