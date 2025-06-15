<?php include_once 'Databases/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Accueil - Garage</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


  <style>
    /* === BASE & BODY === */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4fb;
      color: #333;
      margin: 0;
      padding: 0;
    }

    /* === HERO SECTION (carousel items) === */
    .hero-section {
      position: relative;
      color: white;
      padding: 140px 20px;
      min-height: 420px;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      background-blend-mode: multiply;
      background-color: rgba(0,0,0,0.55);
      filter: brightness(0.75);
      transition: background-image 0.7s ease-in-out;
      box-shadow: inset 0 0 150px rgba(0,0,0,0.5);
    }

    .hero-section > div {
      position: relative;
      filter: none;
      background: none;
      z-index: 10;
    }

    .hero-section h1 {
      font-size: 3.8rem;
      font-weight: 900;
      color: rgb(115, 151, 187);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      animation: fadeInDown 1.2s ease forwards;
      opacity: 0;
      transform: translateY(-30px);
    }

    .hero-section p {
      font-size: 1.5rem;
      font-weight: 500;
      margin-top: 1rem;
      color: #f0f4fb;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
      animation: fadeInUp 1.6s ease forwards;
      opacity: 0;
      transform: translateY(30px);
    }

    .hero-section .btn-danger {
      background-color: rgb(115, 151, 187);
      border: none;
      font-weight: 600;
      font-size: 1.25rem;
      padding: 12px 32px;
      margin-top: 2.5rem;
      border-radius: 50px;
      box-shadow: 0 6px 15px rgb(115 151 187 / 0.5);
      transition: all 0.35s ease;
      opacity: 0;
      animation: fadeInUp 2s ease forwards;
      cursor: pointer;
      user-select: none;
    }

    .hero-section .btn-danger:hover,
    .hero-section .btn-danger:focus {
      background-color: #0d6efd;
      box-shadow: 0 8px 25px rgb(13 110 253 / 0.7);
      transform: scale(1.1);
      outline: none;
      text-decoration: none;
      color: white;
    }

    /* === Animations === */
    @keyframes fadeInDown {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* === Carousel Controls (arrows) === */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.7));
      background-size: 50%, 50%;
    }

    /* === SERVICES SECTION === */
    .services-section {
      background-color: #fff;
      padding: 90px 15px 90px 15px;
    }

    .services-section h2 {
      color: rgb(115, 151, 187);
      font-weight: 900;
      font-size: 2.8rem;
      margin-bottom: 2.5rem;
      position: relative;
      text-align: center;
      letter-spacing: 0.08em;
    }

    .services-section h2::after {
      content: '';
      display: block;
      width: 90px;
      height: 5px;
      background: rgb(115, 151, 187);
      margin: 14px auto 0;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgb(115 151 187 / 0.45);
    }

    /* Cards des services */
    .service-card {
      background-color: #f9fbff;
      border: none;
      border-radius: 18px;
      padding: 30px 25px;
      box-shadow: 0 8px 25px rgb(115 151 187 / 0.15);
      transition: transform 0.35s ease, box-shadow 0.35s ease;
      cursor: default;
      user-select: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 280px;
      text-align: center;
    }

    .service-card:hover {
      transform: translateY(-14px) scale(1.03);
      box-shadow: 0 12px 40px rgb(115 151 187 / 0.30);
      background-color: #eef6ff;
    }

    .service-card h3 {
      color: rgb(115, 151, 187);
      font-weight: 700;
      font-size: 1.9rem;
      margin-bottom: 1rem;
      transition: color 0.3s ease;
    }

    .service-card p {
      color: #6c757d;
      font-size: 1.1rem;
      line-height: 1.5;
      font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
      .hero-section h1 {
        font-size: 2.8rem;
      }
      .hero-section p {
        font-size: 1.1rem;
      }
      .service-card {
        height: auto;
        padding: 25px 15px;
      }
    }
  </style>
</head>
<body>
  <?php include_once 'Layouts/header.php'; ?>

  <div
    id="carouselAccueil"
    class="carousel slide"
    data-bs-ride="carousel"
    data-bs-interval="5000"
  >
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div
        class="carousel-item active"
        style="background-image: url('images/garage1.jpeg');"
      >
        <div class="hero-section">
          <div>
            <h1 class="display-4 fw-bold">Bienvenue</h1>
            <p class="lead">Découvrez nos services de réparation automobile</p>
            <a href="#services" class="btn btn-danger btn-lg mt-3"
              >Voir nos services</a
            >
          </div>
        </div>
      </div>
      <!-- Slide 2 -->
      <div
        class="carousel-item"
        style="background-image: url('images/garage2.jpeg');"
      >
        <div class="hero-section">
          <div>
            <h1 class="display-4 fw-bold">Entretien de qualité</h1>
            <p class="lead">Des experts à votre service</p>
            <a href="#services" class="btn btn-danger btn-lg mt-3"
              >Voir nos services</a
            >
          </div>
        </div>
      </div>
      <!-- Slide 3 -->
      <div
        class="carousel-item"
        style="background-image: url('images/garage3.jpeg');"
      >
        <div class="hero-section">
          <div>
            <h1 class="display-4 fw-bold">Diagnostic moderne</h1>
            <p class="lead">Outils et technologies de pointe</p>
            <a href="#services" class="btn btn-danger btn-lg mt-3"
              >Voir nos services</a
            >
          </div>
        </div>
      </div>
    </div>
    <!-- Controls -->
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carouselAccueil"
      data-bs-slide="prev"
      aria-label="Précédent"
    >
      <span
        class="carousel-control-prev-icon"
        aria-hidden="true"
      ></span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carouselAccueil"
      data-bs-slide="next"
      aria-label="Suivant"
    >
      <span
        class="carousel-control-next-icon"
        aria-hidden="true"
      ></span>
    </button>
  </div>

 <section class="services-section" id="services">
  <div class="container">
    <h2 class="section-title text-center mb-5">Nos Services</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card service-card h-100 text-center p-4 shadow-sm">
          <div class="service-icon mb-3 text-primary">
            <i class="fa-solid fa-tools fa-3x"></i>
          </div>
          <h3 class="service-title">Réparation</h3>
          <p class="service-desc text-muted">Toutes marques, tous modèles</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card service-card h-100 text-center p-4 shadow-sm">
          <div class="service-icon mb-3 text-primary">
            <i class="fa-solid fa-oil-can fa-3x"></i>
          </div>
          <h3 class="service-title">Entretien</h3>
          <p class="service-desc text-muted">Vidange, freinage, climatisation</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card service-card h-100 text-center p-4 shadow-sm">
          <div class="service-icon mb-3 text-primary">
            <i class="fa-solid fa-magnifying-glass-chart fa-3x"></i>
          </div>
          <h3 class="service-title">Diagnostic</h3>
          <p class="service-desc text-muted">Outils professionnels</p>
        </div>
      </div>
    </div>
  </div>
</section>

  <?php include_once 'Layouts/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
