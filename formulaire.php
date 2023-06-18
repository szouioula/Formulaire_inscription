<?php
// Connexion à la base de données
include "PDO_connection.php";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    session_start();

    // Protection contre les attaques CSRF
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $telephone = $_POST['telephone'];

        // Validation des données 

        // Échappement des caractères pour prévenir les injections SQL
        $nom = htmlspecialchars($nom);
        $prenom = htmlspecialchars($prenom);
        $email = htmlspecialchars($email);
        $message = htmlspecialchars($message);
        $telephone = htmlspecialchars($telephone);

        // Insertion des données dans la base de données
        try {
            $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, message, telephone) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $message, $telephone]);
            echo "<script>alert('Formulaire soumis avec succès !');</script>";
        } catch (PDOException $e) {
            die('Erreur lors de l\'enregistrement des données : ' . $e->getMessage());
        }
    } else {
        die('Jeton CSRF invalide');
    }
}

// Génération du jeton CSRF
if (!isset($_SESSION)) {
    session_start();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$_POST='';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cookies.css">
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
    <div class="container">
        <h1>Formulaire</h1>
        <form method="POST" action="">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="message">Message :</label>
            <textarea name="message" id="message" required></textarea>
        </div>

        <div class="form-group">
            <label for="telephone">Numéro de téléphone :</label>
            <input type="tel" name="telephone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" id="telephone" required>
        </div>

        <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        <br>
        <h1>Géolocalisation</h1>
        <button id="find-me" onclick="geoFindMe()" >Show my location</button><br />
            <p id="status"></p>
            <a id="map-link" target="_blank"></a>        
            <div id="map"></div>
    </div>
    <div id="simple-cookie-consent">
        <div class="cookie-consent-container">
            <div class="cookie-consent-notice">
                <h4>Vos paramètres de cookies</h4>
                <hr>
                <p>Les cookies nous permettent de personnaliser le contenu et les annonces, d'offrir des fonctionnalités relatives aux médias sociaux et d'analyser notre trafic.</p>
            </div>
            <div class="cookie-consent-selection">
                <button value="false" class="cookie-consent-deny" id="deny">Refuser</button>
                <button value="true" class="cookie-consent-allow" id="allow">Tout autoriser</button>
            </div>
        </div>
    </div>
    <script>
        const div = document.getElementById("simple-cookie-consent");
        div.addEventListener("click", (event) => {
            if (event.target.id === "deny" || event.target.id === "allow") {
                div.style.display = "none";
            }
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>