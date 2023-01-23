<?php
require_once "header.start_session.php";
require_once dirname(__DIR__) . "/models/Player.php";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

$game_data = $_SESSION["game_data"];

// Vérification que tous les champs soient passés un par un
for ($i = 0; $i < $game_data["player_number"]; $i++)
{
    if (!(isset($_POST["player_name_" . $i])))
    {
        $_SESSION["error_message"] = "Le nom d'un joueur est manquant. Veuillez réessayer.";
        header("Location: /views/player_name.php");
        break;
    }
}

// Vérification que l'on ne réinsère pas plus de joueurs que ce que le jeu a comme informations.
// On sait que l'utilisateur veut essayer d'en rajouter si au moins un joueur peuple le tableau players
if (isset($game_data["players"])) {
    header("Location: /");
    exit(0);
}

// Si tous les champs sont OK, on crée un objet pour chacun des joueurs renseignés
for ($j = 0; $j < $game_data["player_number"]; $j++)
{
    // Création du nouveau joueur que l'on va immédiatement rajouter dans le jeu
    $new_player = new Player($_POST["player_name_" . $j]);
    $game_data["players"][] = serialize($new_player);
}

$_SESSION["game_data"] = $game_data;

header("Location: /");
exit(0);
