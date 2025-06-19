<?php
session_start();
include_once '../Databases/fonction.php'; // suffit, car db.php est déjà inclus dans les fonctions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $utilisateur = connectionUtilisateur($email, $password);

    if ($utilisateur) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['role'] = $utilisateur['role'];
        $_SESSION['nom'] = $utilisateur['nom']; // ou prénom, ou concaténé selon ta table

        switch ($utilisateur['role']) {
            case 'admin':
                header('Location: ../admin/dashboard_admin.php');
                break;
            case 'client':
                header('Location: ../client/dashboard_client.php');
                break;
            case 'mecanicien':
                header('Location: ../mecanicien/dashboard_mecanicien.php');
                break;
        }
        exit();
    } else {
        $error_message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Garage AutoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --gmi: rgb(115, 151, 187);
      --gmi-dark: rgb(95, 131, 167);
    }
    body {
      background: linear-gradient(to right, #e3edf7, #f5fafe);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: var(--gmi);
    }
    .form-container {
      flex-grow: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 15px;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      padding: 40px;
      max-width: 400px;
      width: 100%;
      animation: fadeIn 1s ease;
    }
    .login-card h2 {
      text-align: center;
      margin-bottom: 30px;
      color: var(--gmi-dark);
    }
    .btn-primary {
      background-color: var(--gmi);
      border: none;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background-color: var(--gmi-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(115, 151, 187, 0.5);
    }
    footer {
      text-align: center;
      padding: 20px 0;
      color: #888;
      background-color: #fff;
      box-shadow: 0 -1px 6px rgba(0, 0, 0, 0.05);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="/garage/index.php">
        <i class="bi bi-tools me-2"></i>Garage AutoPlus
      </a>
    </div>
  </nav>

  <div class="form-container">
    <div class="login-card">
      <h2>Connexion</h2>
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Adresse Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> Garage AutoPlus. Tous droits réservés.
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
