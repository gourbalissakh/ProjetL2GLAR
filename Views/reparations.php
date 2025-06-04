<?php
if(!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$vehicule_id = $_GET['vehicule_id'] ?? null;
$reparations = [];

if($vehicule_id) {
    $reparations = getReparationsByVehiculeId($vehicule_id);
} elseif($_SESSION['user']['role'] === 'mecanicien') {
    // Pour les mécaniciens, afficher toutes les réparations
    // Vous devrez peut-être créer une nouvelle fonction dans fonction.php
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Réparations</h2>
    
    <?php if($_SESSION['user']['role'] === 'client' && !$vehicule_id): ?>
        <div class="alert alert-info">
            Sélectionnez un véhicule pour voir ses réparations
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reparations as $reparation): ?>
                <tr>
                    <td><?= htmlspecialchars($reparation['date_reparation']) ?></td>
                    <td><?= htmlspecialchars($reparation['description']) ?></td>
                    <td>
                        <a href="factures.php?reparation_id=<?= $reparation['id'] ?>" class="btn btn-sm btn-info">Voir facture</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php';
?>
