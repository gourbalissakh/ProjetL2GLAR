<?php
// header.php
?>

<!-- HEADER NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm px-4 sticky-top">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="images/logo1.png" alt="Logo Garage" width="55" height="55" class="me-2 rounded-circle">
    <span class="fs-4 fw-bold text-white">Garage AutoPlus</span>
  </a>

  <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav align-items-lg-center gap-4">
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-1" href="index.php">
          <i class="bi bi-house-door-fill"></i> Accueil
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-1" href="#services">
          <i class="bi bi-tools"></i> Services
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-1" href="#footer">
          <i class="bi bi-envelope-fill"></i> Contact
        </a>
      </li>
      <li class="nav-item">
        <a class="btn btn-light text-primary d-flex align-items-center gap-2 px-3 fw-bold" href="../Views/connexion.php">
          <i class="bi bi-box-arrow-in-right"></i> Connexion
        </a>
      </li>
    </ul>
  </div>
</nav>

<!-- STYLE DU HEADER -->
<style>
  .navbar-custom {
    background-color: rgb(115, 151, 187);
  }

  .navbar-custom .nav-link {
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .navbar-custom .nav-link:hover {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 5px 10px;
  }

  .navbar-custom .navbar-toggler {
    filter: invert(1);
  }

  .btn-light.text-primary:hover {
    background-color: #0d6efd;
    color: white;
  }
</style>

<!-- BOOTSTRAP & ICONS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
