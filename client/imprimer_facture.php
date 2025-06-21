<?php
require_once '../Databases/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_GET['id'])) {
    die("Facture non spécifiée.");
}

$id = (int) $_GET['id'];

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

// Logo en chemin absolu
$logoPath = realpath(__DIR__ . '/../images/logooo.png');
if (!file_exists($logoPath)) {
    die("Logo introuvable.");
}

// HTML du PDF
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
      padding: 20px;
      color: #333;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .header img {
      height: 70px;
    }
    .section {
      margin-bottom: 15px;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 10px;
    }
    .section h3 {
      margin: 0 0 5px;
      font-size: 16px;
      color: #0066cc;
    }
    .footer {
      text-align: center;
      font-size: 10px;
      margin-top: 30px;
      color: #888;
    }
  </style>
</head>
<body>

<div class="header">
  <img src="file://<?= $logoPath ?>" alt="Logo Garage AutoPlus">
  <h1>Facture de Réparation</h1>
  <strong>N° Facture : <?= $facture['facture_id'] ?></strong>
</div>

<div class="section">
  <h3>Client</h3>
  <p><strong>Nom :</strong> <?= htmlspecialchars($facture['nom_client']) ?> <?= htmlspecialchars($facture['prenom_client']) ?></p>
</div>

<div class="section">
  <h3>Véhicule</h3>
  <p><strong>Marque :</strong> <?= htmlspecialchars($facture['marque']) ?></p>
  <p><strong>Immatriculation :</strong> <?= htmlspecialchars($facture['immatriculation']) ?></p>
</div>

<div class="section">
  <h3>Réparation</h3>
  <p><strong>Date :</strong> <?= $facture['date_reparation'] ?></p>
  <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($facture['desc_reparation'])) ?></p>
</div>

<div class="section">
  <h3>Facturation</h3>
  <p><strong>Date :</strong> <?= $facture['date_facture'] ?></p>
  <p><strong>Montant :</strong> <?= number_format($facture['montant'], 0, ',', ' ') ?> FCFA</p>
</div>

<div class="section">
  <h3>Garage AutoPlus</h3>
  <p>Garage AutoPlus est votre partenaire de confiance pour la réparation automobile.</p>
  <p><strong>Adresse :</strong> Quartier Ndjari, N’Djamena</p>
  <p><strong>Tél :</strong> +235 66 66 66 66</p>
  <p><strong>Web :</strong> www.autoplus.td</p>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Garage AutoPlus - Tous droits réservés
</div>
</body>
</html>
<?php
$html = ob_get_clean();

// PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
ob_end_clean(); // éviter toute sortie HTML
$dompdf->render();
$dompdf->stream("facture_{$facture['facture_id']}.pdf", ["Attachment" => false]);
exit;
