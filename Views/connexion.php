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
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, rgb(115, 151, 187), #f8f9fa);
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    header {
      background: rgb(115, 151, 187);
      color: white;
      padding: 18px 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }

    header .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    header nav a {
      color: white;
      margin-left: 15px;
      text-decoration: none;
      font-weight: 600;
    }

    header nav a:hover {
      text-decoration: underline;
      color: #e6e6e6;
    }

    main {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 100px;
    }

    .card {
      width: 100%;
      max-width: 420px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      background: white;
      animation: fadeIn 0.8s ease;
    }

    .card-header {
      background-color: rgb(115, 151, 187);
      color: white;
      text-align: center;
      padding: 30px 20px;
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

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    footer {
      text-align: center;
      font-size: 0.9rem;
      padding: 10px;
      background-color: #f1f1f1;
      color: #666;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container">
      <h1 class="h5 mb-0">
        <i class="bi bi-tools me-2"></i>Garage AutoPlus
      </h1>
      <nav>
        <a href="/index.php">Accueil</a>
        <a href="#footer">Contact</a>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main>
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
    </div>
  </main>

  <!-- Footer -->
 <?php include_once '../Layouts/footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
