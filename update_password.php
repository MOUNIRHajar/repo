<?php
//123321
$email = $temporaryPassword = ""; // Définissez les variables pour éviter des erreurs potentielles

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"]) && isset($_GET["temporary_password"])) {
    $email = $_GET["email"];
    $temporaryPassword = $_GET["temporary_password"];
    
} else {
    echo "Paramètres d'URL manquants ou incorrects.";
    exit; // Arrêtez le script si les paramètres sont manquants ou incorrects
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mise à jour du mot de passe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Mise à jour du mot de passe</h1>
                <form method="post" action="update_password_process.php">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="temporary_password" value="<?php echo htmlspecialchars($temporaryPassword); ?>">
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe :</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Mettre à jour le mot de passe</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

