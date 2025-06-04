<?php
require_once '../database/fonction.php';
if(!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer les données selon le rôle
$userRole = $_SESSION['user']['role'];
$data = [];

switch($userRole) {
    case 'admin':
        $data['users'] = getAllUtilisateurs();
        break;
    case 'client':
        $data['vehicules'] = getVehiculesByUserId($_SESSION['user']['id']);
        break;
    case 'mecanicien':
        // Vous pouvez ajouter des données spécifiques aux mécaniciens
        break;
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Tableau de Bord</h2>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['prenom']) ?>!</p>
    
    <?php if($userRole === 'admin'): ?>
        <div class="card mb-4">
            <div class="card-header">Utilisateurs</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['users'] as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif($userRole === 'client'): ?>
        <div class="card mb-4">
            <div class="card-header">Mes Véhicules</div>
            <div class="card-body">
                <?php if(count($data['vehicules']) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Marque</th>
                                <th>Immatriculation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['vehicules'] as $vehicule): ?>
                            <tr>
                                <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                                <td><?= htmlspecialchars($vehicule['immatriculation']) ?></td>
                                <td>
                                    <a href="vehicules.php?action=view&id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Vous n'avez aucun véhicule enregistré.</p>
                    <a href="vehicules.php?action=add" class="btn btn-primary">Ajouter un véhicule</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php';
?>
