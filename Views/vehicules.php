<?php
require_once '../database/fonction.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header('Location: connexion.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Traitement du formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($action === 'add') {
        $marque = $_POST['marque'];
        $immatriculation = $_POST['immatriculation'];
        $id_utilisateur = $_SESSION['user']['id'];
        
        if(AjouterVehicule($marque, $immatriculation, $id_utilisateur)) {
            $_SESSION['message'] = "Véhicule ajouté avec succès";
            header('Location: vehicules.php');
            exit;
        }
    }
}

$vehicules = getVehiculesByUserId($_SESSION['user']['id']);
?>

<?php include '../layouts/header.php'; ?>

<div class="container mt-5">
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if($action === 'list'): ?>
        <h2>Mes Véhicules</h2>
        <a href="?action=add" class="btn btn-primary mb-3">Ajouter un véhicule</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Marque</th>
                    <th>Immatriculation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vehicules as $vehicule): ?>
                <tr>
                    <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                    <td><?= htmlspecialchars($vehicule['immatriculation']) ?></td>
                    <td>
                        <a href="?action=view&id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                        <a href="?action=edit&id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="?action=delete&id=<?= $vehicule['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    <?php elseif($action === 'add'): ?>
        <h2>Ajouter un véhicule</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Marque</label>
                <input type="text" name="marque" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Immatriculation</label>
                <input type="text" name="immatriculation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="vehicules.php" class="btn btn-secondary">Annuler</a>
        </form>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php';
?>
