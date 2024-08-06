<?php
session_start();

// Vérifier si l'identifiant du candidat à modifier est passé en paramètre
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_cand = $_GET['id'];

    try {
        $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer les données du candidat avec l'ID spécifié
        $stmt = $db->prepare("SELECT * FROM candidat WHERE id_cand = :id_cand");
        $stmt->bindParam(':id_cand', $id_cand);
        $stmt->execute();
        $candidat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($candidat) {
            // Récupération des données du candidat pour pré-remplir le formulaire
            $nom = $candidat['nom'];
            $prenom = $candidat['prenom'];
            $datnais = $candidat['datnais'];
            $ville = $candidat['ville'];
            $sexe = $candidat['sexe'];
            $codefil = $candidat['codefil'];
        } else {
            echo "Aucun candidat trouvé avec l'ID spécifié.";
            die();
        }

    } catch (PDOException $e) {
        echo "Échec de la connexion : " . $e->getMessage();
        die();
    }
}

// Si le formulaire de mise à jour est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id_cand = $_POST['id_cand'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $datnais = $_POST['datnais'];
    $ville = $_POST['ville'];
    $sexe = $_POST['sexe'];
    $codefil = $_POST['codefil'];

    try {
        $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour mettre à jour les données du candidat
        $stmt_update = $db->prepare("UPDATE candidat SET nom = :nom, prenom = :prenom, datnais = :datnais, ville = :ville, sexe = :sexe, codefil = :codefil WHERE id_cand = :id_cand");

        // Exécution de la requête de mise à jour
        $stmt_update->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'datnais' => $datnais,
            'ville' => $ville,
            'sexe' => $sexe,
            'codefil' => $codefil,
            'id_cand' => $id_cand
        ]);

        echo "Données mises à jour avec succès.";

        // Redirection vers candidat.php après la mise à jour
        header("Location: candidat.php");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification des informations du candidat</title>
</head>
<body>
    <h2>Modification des informations du candidat</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id_cand" value="<?php echo htmlspecialchars($id_cand); ?>">
        
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>"><br><br>
        
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>"><br><br>
        
        <label for="datnais">Date de naissance:</label>
        <input type="date" id="datnais" name="datnais" value="<?php echo htmlspecialchars($datnais); ?>"><br><br>
        
        <label for="ville">Ville:</label>
        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($ville); ?>"><br><br>
        
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe">
            <option value="M" <?php if ($sexe == 'M') echo 'selected'; ?>>Masculin</option>
            <option value="F" <?php if ($sexe == 'F') echo 'selected'; ?>>Féminin</option>
        </select><br><br>
        
        <tr>
                <td>Codefil:</td>
                <td>
                    <select name="codefil" required>
                        <option value="SIL">SIL</option>
                        <option value="RIT">RIT</option>
                        <option value="AGRO">AGRO</option>
                        <option value="AGE">AGE</option>
                    </select>
                </td>
            </tr>
        
      
            <br><br><input type="submit" name="submit" value="Mettre à jour"><br><br>
    </form>
</body>
</html>