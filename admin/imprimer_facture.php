<?php
require_once '../Databases/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

// Vérifie que l'ID est fourni
if (!isset($_GET['id'])) {
    die("Facture non spécifiée.");
}

$id = (int) $_GET['id'];

// Récupère les infos de la facture
$stmt = $pdo->prepare("
    SELECT f.*, f.id AS facture_id, r.date_reparation, r.description AS desc_reparation,
           v.marque, v.immatriculation,
           u.nom AS nom_client, u.prenom AS prenom_client
    FROM facture f
    JOIN reparation r ON f.id_reparation = r.id
    JOIN vehicule v ON r.id_vehicule = v.id
    JOIN utilisateur u ON v.id_utilisateur = u.id
    WHERE f.id = ?
");

$stmt->execute([$id]);
$facture = $stmt->fetch();

if (!$facture) {
    die("Facture introuvable.");
}

// Contenu HTML du PDF
ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #333;
      padding: 20px;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .header img {
      height: 70px;
    }
    h1 {
      font-size: 20px;
      color: #0056b3;
      margin-bottom: 5px;
    }
    .info {
      margin-bottom: 20px;
    }
    .info strong {
      display: inline-block;
      width: 150px;
    }
    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 11px;
      color: #777;
    }
    .box {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="header">
  <img src="../images/logooo.jpg" alt="Logo Garage AutoPlus"><br>
  <h1>Garage AutoPlus</h1>
  <p><strong>Facture #<?= $facture['facture_id'] ?></strong></p>
</div>

<div class="box">
  <h3>Informations Client</h3>
  <p><strong>Nom :</strong> <?= htmlspecialchars($facture['nom_client']) ?> <?= htmlspecialchars($facture['prenom_client']) ?></p>
</div>

<div class="box">
  <h3>Détails Véhicule</h3>
  <p><strong>Marque :</strong> <?= htmlspecialchars($facture['marque']) ?></p>
  <p><strong>Immatriculation :</strong> <?= htmlspecialchars($facture['immatriculation']) ?></p>
</div>

<div class="box">
  <h3>Réparation</h3>
  <p><strong>Date :</strong> <?= $facture['date_reparation'] ?></p>
  <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($facture['desc_reparation'])) ?></p>
</div>

<div class="box">
  <h3>Facturation</h3>
  <p><strong>Date de Facture :</strong> <?= $facture['date_facture'] ?></p>
  <p><strong>Montant :</strong> <?= number_format($facture['montant'], 2, ',', ' ') ?> FCFA</p>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Garage AutoPlus - www.autoplus.td
</div>

</body>
</html>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Orientation portrait A4
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Téléchargement ou affichage
$dompdf->stream("facture_{$facture['facture_id']}.pdf", ["Attachment" => false]);
exit;
