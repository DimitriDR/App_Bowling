<?php
require_once "header.start_session.php";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

// Vérification du type de la variable donnée
if (!is_numeric($_POST["playersnumber"]))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header("Location: /");
}

$player_number = (int)$_POST["playersnumber"];

// Vérification du nombre de joueurs
if ($player_number <= 0 || $player_number > 20)
{
    $_SESSION["error_message"] = "Le nombre de joueurs n'est pas compris dans les bornes prévues.";
    header("Location: /");
}

// Ajout du nombre de joueurs dans la partie, maintenant que l'entrée a été rendue propre
$_SESSION["game_data"]["player_number"] = $player_number;
header("Location: /views/player_name.php");
