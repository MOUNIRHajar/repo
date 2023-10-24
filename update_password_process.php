<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $temporaryPassword =$_POST["temporary_password"]; 
    $newPassword = md5($_POST["new_password"]); // Utilisation de MD5 pour le nouveau mot de passe

    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "Biblioclic";

        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifiez si le mot de passe temporaire est valide en le comparant à la base de données
        $stmt = $conn->prepare("SELECT idadmin, temporary_password FROM admin WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedTemporaryPassword = $row["temporary_password"];
            
            // Vérifiez si le mot de passe temporaire saisi correspond au mot de passe stocké dans la base de données
            if ($temporaryPassword === $storedTemporaryPassword) {
                // Mettez à jour le mot de passe de l'utilisateur dans la base de données avec le nouveau mot de passe 
                $updateStmt = $conn->prepare("UPDATE admin SET mot_de_passe = :mot_de_passe, temporary_password = NULL WHERE email = :email");
                $updateStmt->bindParam(":mot_de_passe", $newPassword);
                $updateStmt->bindParam(":email", $email);
                $updateStmt->execute();

                echo "Le mot de passe a été mis à jour avec succès. Vous pouvez maintenant vous connecter avec le nouveau mot de passe .";
                
            } else {
                echo "Mot de passe temporaire invalide. Veuillez réessayer.";
            }
        } else {
            echo "Aucun administrateur trouvé avec cette adresse e-mail.";
        }
    } catch (PDOException $e) {
        echo "La connexion à la base de données a échoué : " . $e->getMessage();
    }
} else {
    echo "Requête invalide.";
}

?>
