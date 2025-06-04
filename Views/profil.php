<?php
if(!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user']['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'] ?? '';
    
    if(mettreAJourUtilisateur($id, $nom, $prenom, $email, $password)) {
        $_SESSION['message'] = "Profil mis à jour avec succès";
        $_SESSION['user'] = array_merge($_SESSION['user'], [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ]);
        header('Location: profil.php');
        exit;
    }
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Mon Profil</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php include '../layouts/footer.php';
?>
