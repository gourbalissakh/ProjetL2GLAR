<?php
session_start();
require_once '../Databases/db.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Views/connexion.php');
    exit;
}

$clientId = $_SESSION['utilisateur_id'];
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';

// Récupère réparations + diagnostic + facture
$stmt = $pdo->prepare("
    SELECT r.*, v.marque, v.immatriculation,
           d.description AS diagnostic_desc, d.date_diagnostic,
           f.id AS facture_id, f.montant, f.date_facture
    FROM reparation r
    JOIN vehicule v ON r.id_vehicule = v.id
    LEFT JOIN diagnostic d ON d.id_reparation = r.id
    LEFT JOIN facture f ON f.id_reparation = r.id
    WHERE v.id_utilisateur = ?
    ORDER BY r.date_reparation DESC
");
$stmt->execute([$clientId]);
$reparations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Mes Réparations - Garage AutoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .table-hover tbody tr:hover {
      background-color: rgb(115 151 187 / 0.15);
    }
  </style>
</head>
<body>
<?php include 'header_client.php'; ?>

<div class="container my-5">
  <h2 class="mb-4 text-primary fw-bold">
    <i class="bi bi-tools"></i> Historique des Réparations
  </h2>

  <div class="table-responsive shadow rounded">
    <table class="table table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>Véhicule</th>
          <th>Date Réparation</th>
          <th>Description</th>
          <th>Diagnostic</th>
          <th>Montant Facture</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($reparations) > 0): ?>
          <?php foreach ($reparations as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['marque'] . ' - ' . $r['immatriculation']) ?></td>
              <td><?= htmlspecialchars($r['date_reparation']) ?></td>
              <td><?= nl2br(htmlspecialchars($r['description'])) ?></td>
              <td>
                <?php if ($r['diagnostic_desc']): ?>
                  <strong><?= $r['date_diagnostic'] ?></strong><br>
                  <?= nl2br(htmlspecialchars($r['diagnostic_desc'])) ?>
                <?php else: ?>
                  <span class="text-muted">Aucun</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($r['montant']): ?>
                  <span class="badge bg-success"><?= number_format($r['montant'], 0, ',', ' ') ?> FCFA</span><br>
                  <small><?= $r['date_facture'] ?></small>
                <?php else: ?>
                  <span class="text-muted">Non générée</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($r['facture_id']): ?>
                  <a href="imprimer_facture.php?id=<?= $r['facture_id'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Télécharger
                  </a>
                <?php else: ?>
                  <button class="btn btn-sm btn-secondary disabled">
                    <i class="bi bi-file-earmark"></i> En attente
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">Aucune réparation trouvée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
