<?php
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

// Vérification de la suppression d'un livre
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id']) && !empty($_GET['id'])) {
    $livre_id = $_GET['id'];

    // Requête de suppression pour retirer le livre de la base de données
    $requete_supprimer_livre = $connexion->prepare("DELETE FROM livres WHERE id = ?");
    $requete_supprimer_livre->bind_param("i", $livre_id);
    $resultat_supprimer_livre = $requete_supprimer_livre->execute();

    if ($resultat_supprimer_livre === true) {
        echo "Le livre a été supprimé avec succès !";
    } else {
        echo "Erreur lors de la suppression du livre : " . $requete_supprimer_livre->error;
    }
}

// Requête de sélection pour récupérer les livres depuis la base de données
$requete_select_livres = "SELECT id, titre, genre, auteur, note, date_creation, date_modification FROM livres";
$resultat_select_livres = $connexion->query($requete_select_livres);

// Vérifier si la requête a retourné des résultats
if ($resultat_select_livres->num_rows > 0) {
    // Affichage des livres sous forme de tableau avec boutons de modification et de suppression
    echo "<h2>Liste des livres enregistrés</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Genre</th>
                <th>Auteur</th>
                <th>Note</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Actions</th>
            </tr>";

    while ($ligne = $resultat_select_livres->fetch_assoc()) {
        echo "<tr>
                <td>" . $ligne['id'] . "</td>
                <td>" . $ligne['titre'] . "</td>
                <td>" . $ligne['genre'] . "</td>
                <td>" . $ligne['auteur'] . "</td>
                <td>" . $ligne['note'] . "</td>
                <td>" . $ligne['date_creation'] . "</td>
                <td>" . $ligne['date_modification'] . "</td>
                <td>
                    <a href='modifier_livre.php?id=" . $ligne['id'] . "'>Modifier</a>
                    <a href='afficher_livres.php?action=supprimer&id=" . $ligne['id'] . "'>Supprimer</a>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "Aucun livre enregistré dans la base de données.";
}

// Fermer la connexion à la base de données
$connexion->close();
?>
