<?php
require_once "header.start_session.php";
require_once dirname(__DIR__) . "/models/Player.php";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

// Vérification que tous les champs soient passés un par un

$game_data = $_SESSION["game_data"];

for ($i = 0; $i < $game_data["player_number"] ; $i++)
{
    if (!(isset($_POST["player_name_".$i])))
    {
        $_SESSION["error_message"] = "Le nom d'un joueur est manquant. Veuillez réessayer.";
        header("Location: /views/player_name.php");
        break;
    } else {
        continue;
    }
}

// Si tous les champs sont OK, on crée un objet pour chacun des joueurs renseignés
for ($j = 0; $j < $game_data["player_number"] ; $j++)
{
    // On vérifie qu'on n'a pas plus de joueurs que ceux déclarés (si l'utilisateur retourne sur le formulaire par ex.)
    $current_player_object_number = sizeof($game_data["players"]);
    if (($current_player_object_number + 1) > $current_player_object_number)
    {
        break;
    }

    // Création du nouveau joueur que l'on va immédiatement rajouter dans le jeu
    $new_player = new Player($_POST["player_name_" . $j]);
    $game_data["players"][] = serialize($new_player);
}

header("Location: /");
exit(0);
