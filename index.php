<?php
include_once 'Databases/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../images/garage-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .brand-title {
            color: #d40000;
            font-weight: 700;
        }
        
        .service-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body class='body-class'>
    <!-- Include header -->
    <?php include_once 'Layouts/header.php'; ?>

<section class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold text-white">Bienvenue</h1>
        <p class="lead text-white">Découvrez nos services de réparation automobile</p>
        <a href="#services" class="btn btn-danger btn-lg mt-3">Voir nos services</a>
    </div>
</section>

    <!-- Services Section  -->
   <section class="services-section py-5 " >
        <div class="container">
            <h2 class="text-center mb-5"> Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title">Réparation</h3>
                            <p class="card-text">Toutes marques, tous modèles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title">Entretien</h3>
                            <p class="card-text">Vidange, freinage, climatisation</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card h-100">
                        <div class="card-body text-center">
                            <h3 class="card-title">Diagnostic</h3>
                            <p class="card-text">Outils professionnels</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- footer -->
    <?php include_once 'Layouts/footer.php'; ?>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>