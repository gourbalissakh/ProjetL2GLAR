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

// Préparer le logo en base64 (pour qu'il s'affiche même en PDF)
$logoPath = '../images/logooo.png';
if (!file_exists($logoPath)) {
    die("Logo introuvable.");
}
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/jpeg;base64,' . $logoData;

// Début du contenu HTML
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
      padding: 30px;
    }
    .header {
      text-align: center;
      margin-bottom: 15px;
    }
    .logo {
      height: 60px;
      margin-bottom: 8px;
    }
    h1 {
      font-size: 20px;
      color: rgb(88, 119, 149);
      margin: 5px 0;
    }
    .section {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px 15px;
      margin-bottom: 10px;
    }
    .section h3 {
      font-size: 14px;
      margin-bottom: 6px;
      color: rgb(115, 151, 187);
    }
    .row {
      margin-bottom: 4px;
    }
    .label {
      font-weight: bold;
      display: inline-block;
      width: 130px;
    }
    .footer {
      margin-top: 25px;
      text-align: center;
      font-size: 10px;
      color: #999;
    }
    .garage-info p {
  margin: 3px 0;
  font-size: 11px;
  color: #555;
}
.garage-info a:hover {
  text-decoration: underline;
}

  </style>
</head>
<body>

<div class="header">
  <img src="<?= $logoSrc ?>" class="logo" alt="Logo AutoPlus">
  <h1>Facture n°<?= $facture['facture_id'] ?></h1>
</div>

<div class="section">
  <h3>Client</h3>
  <div class="row"><span class="label">Nom :</span> <?= htmlspecialchars($facture['nom_client']) ?> <?= htmlspecialchars($facture['prenom_client']) ?></div>
</div>

<div class="section">
  <h3>Véhicule</h3>
  <div class="row"><span class="label">Marque :</span> <?= htmlspecialchars($facture['marque']) ?></div>
  <div class="row"><span class="label">Immatriculation :</span> <?= htmlspecialchars($facture['immatriculation']) ?></div>
</div>

<div class="section">
  <h3>Réparation</h3>
  <div class="row"><span class="label">Date :</span> <?= $facture['date_reparation'] ?></div>
  <div class="row"><span class="label">Description :</span> <?= nl2br(htmlspecialchars($facture['desc_reparation'])) ?></div>
</div>

<div class="section">
  <h3>Montant</h3>
  <div class="row"><span class="label">Date Facture :</span> <?= $facture['date_facture'] ?></div>
  <div class="row"><span class="label">Montant :</span> <strong><?= number_format($facture['montant'], 2, ',', ' ') ?> FCFA</strong></div>
</div>
<div class="garage-info section">
  <h3>Garage AutoPlus</h3>
  <p>Adresse : 123 Rue de l’Entretien, N’Djamena, Tchad</p>
  <p>Téléphone : +235 123 456 789</p>
  <p>Email : contact@autoplus.td</p>
  <p>Site web : <a href="http://www.autoplus.td" style="color: rgb(115, 151, 187); text-decoration:none;">www.autoplus.td</a></p>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Garage AutoPlus - www.autoplus.td<br>
  Merci pour votre confiance 🙏
</div>

</body>
</html>

<?php
$html = ob_get_clean();

// Génération PDF avec Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// A4 Portrait
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Affichage dans le navigateur (PDF)
$dompdf->stream("facture_{$facture['facture_id']}.pdf", ["Attachment" => false]);
exit;
