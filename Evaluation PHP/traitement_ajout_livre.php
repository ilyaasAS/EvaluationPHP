<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire et les nettoyer
    $titre = htmlspecialchars(trim($_POST["titre"]));
    $genre = htmlspecialchars(trim($_POST["genre"]));
    $auteur = htmlspecialchars(trim($_POST["auteur"]));
    $note = floatval($_POST["note"]);

    // Vérifier que le titre n'est pas vide
    if (empty($titre)) {
        die("Le titre du livre ne peut pas être vide.");
    }

    // Connexion à la base de données (remplacez ces informations par les vôtres)
    $serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$basededonnees = "evaluation";

    $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

    // Vérifier si la connexion a échoué
    if ($connexion->connect_error) {
        die("Erreur de connexion à la base de données : " . $connexion->connect_error);
    }

    // Vérifier si le livre avec le même titre existe déjà dans la base de données
    $requete_verif_titre = $connexion->prepare("SELECT id FROM livres WHERE titre = ?");
    $requete_verif_titre->bind_param("s", $titre);
    $requete_verif_titre->execute();
    $resultat_verif_titre = $requete_verif_titre->get_result();

    if ($resultat_verif_titre->num_rows > 0) {
        die("Un livre avec ce titre existe déjà dans la base de données.");
    }

    // Préparer la requête d'insertion des données dans la table "livres"
    $requete = $connexion->prepare("INSERT INTO livres (titre, genre, auteur, note) VALUES (?, ?, ?, ?)");

    // Vérifier si la préparation de la requête a échoué
    if ($requete === false) {
        die("Erreur de préparation de la requête : " . $connexion->error);
    }

    // Lier les paramètres de la requête et exécuter l'insertion
    $requete->bind_param("sssd", $titre, $genre, $auteur, $note);
    $resultat = $requete->execute();

    // Vérifier si l'insertion a réussi
    if ($resultat === true) {
        echo "Le livre a été ajouté avec succès !";
    } else {
        echo "Erreur lors de l'ajout du livre : " . $requete->error;
    }

    // Fermer les requêtes et la connexion à la base de données
    $requete_verif_titre->close();
    $requete->close();
    $connexion->close();
}
?>

