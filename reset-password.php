<?php
require_once('mail.php');

// Fonction pour générer un mot de passe temporaire
function generateTemporaryPassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Biblioclic";

    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];

        // Vérification si l'e-mail existe dans la table "admin"
        $stmt = $conn->prepare("SELECT idadmin FROM admin WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $temporaryPassword = generateTemporaryPassword(12);

            // Insérer le mot de passe temporaire dans la base de données pour cet utilisateur
            $insertStmt = $conn->prepare("UPDATE admin SET temporary_password = :temporary_password WHERE email = :email");
            $insertStmt->bindParam(":temporary_password", $temporaryPassword);
            $insertStmt->bindParam(":email", $email);
            $insertStmt->execute();

            // Envoi de l'e-mail avec le mot de passe temporaire
            require_once('mail.php');
            $mail->setFrom('votre_email@gmail.com', 'Votre nom');
            $mail->addAddress($email);

            $mail->Subject = "Mot de passe temporaire Biblioclic";
            $mail->Body = "Voici votre mot de passe temporaire : $temporaryPassword";

            if ($mail->send()) {
                echo "Un mot de passe temporaire a été envoyé à votre adresse e-mail.";
                // Rediriger vers update_password.php avec les paramètres d'URL
                header("Location: update_password.php?email=$email&temporary_password=$temporaryPassword");
            } else {
                echo "L'envoi de l'e-mail a échoué : " . $mail->ErrorInfo;
            }
        } else {
            echo "Aucun administrateur trouvé avec cette adresse e-mail.";
        }
    }
} catch (PDOException $e) {
    echo "La connexion à la base de données a échoué : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'e-mail : " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Récupération de mot de passe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Récupération de mot de passe</h1>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Adresse e-mail :</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

