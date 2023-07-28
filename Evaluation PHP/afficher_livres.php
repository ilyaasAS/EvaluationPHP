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

// Requête de sélection pour récupérer les livres depuis la base de données
$requete_select_livres = "SELECT id, titre, genre, auteur, note, date_creation, date_modification FROM livres";
$resultat_select_livres = $connexion->query($requete_select_livres);

// Vérifier si la requête a retourné des résultats
if ($resultat_select_livres->num_rows > 0) {
    // Affichage des livres sous forme de tableau avec bouton de modification
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
                <th>Action</th>
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
                <td><a href='modifier_livre.php?id=" . $ligne['id'] . "'>Modifier</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "Aucun livre enregistré dans la base de données.";
}

// Fermer la connexion à la base de données
$connexion->close();
?>
