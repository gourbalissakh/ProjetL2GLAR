<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Footer Test</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
  body {
    margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .bg-custom {
    background-color: rgb(115, 151, 187) !important;
  }
  footer {
    color: white;
  }
  .footer-title {
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 1rem;
    border-bottom: 3px solid white;
    display: inline-block;
    padding-bottom: 4px;
  }
  footer ul li {
    margin-bottom: 0.7rem;
    font-size: 1rem;
  }
  .social-icons a {
    color: white;
    margin-right: 1rem;
    font-size: 1.5rem;
    transition: color 0.3s ease;
  }
  .social-icons a:hover {
    color: #0d6efd;
  }
  hr.border-light {
    border-color: rgba(255, 255, 255, 0.4);
    margin-top: 1rem;
    margin-bottom: 1rem;
  }
  @media (max-width: 575px) {
    footer {
      text-align: center;
    }
    .social-icons a {
      margin-right: 1rem;
    }
  }
</style>
</head>
<body>

<footer class="footer bg-custom pt-5 pb-4" id="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <h5 class="footer-title">Garage AutoPlus</h5>
        <p>Votre partenaire de confiance pour tous travaux de réparation et entretien automobile.</p>
      </div>
      <div class="col-md-4 mb-4">
        <h5 class="footer-title">Contact</h5>
        <ul class="list-unstyled">
          <li><i class="fa-solid fa-location-dot me-2"></i>123 Rue du Garage, Dakar, Sénégal</li>
          <li><i class="fa-solid fa-phone me-2"></i>+221 77 123 45 67</li>
          <li><i class="fa-solid fa-envelope me-2"></i>contact@garageautoplus.sn</li>
        </ul>
      </div>
      <div class="col-md-4 mb-4">
        <h5 class="footer-title">Suivez-nous</h5>
        <div class="social-icons">
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>
    </div>
    <hr class="border-light" />
    <div class="text-center small">
      &copy; 2025 Garage AutoPlus. Tous droits réservés.
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
